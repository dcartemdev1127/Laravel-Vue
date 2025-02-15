<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Expertise;
use App\Models\Message;
use App\Models\Service;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserReviews;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request):View {
        $subjectTypes = getSubjectTypes();
        if($request->user()->role === 'tutor') {
            $user = $request->user();
            $tutor = $user->tutor()->first();
            $data['profile_views'] = $tutor->view_num;
            $data['stp'] = $tutor->points()->sum('score');
            $data['stp_by_client'] = $tutor->points()->whereNotNull('parent_id')->sum('score');
            $data['completedjobs'] = $user->tutorBookings()->where('status', '>' , 3)->count();
            $data['retention_rate'] = $user->bookings()->count();
            $reviews = $user->reviews()->count();
            if($reviews === 0) $data['average_rating'] = 0;
            else $data['average_rating'] = $user->reviews()->sum('score') / $reviews;
            $data['reviews'] = $reviews;
            $data['bookings'] = Booking::where('person_id', $user->id)->where('status', 2)->count();
            $minDate = Transactions::where('tutor_id', $user->id)->where('status', 2)->min('created_at');
            $maxDate = Transactions::where('tutor_id', $user->id)->where('status', 2)->max('created_at');
            if(!$minDate || !$maxDate) {
                $data['revenues'] = [
                    'dates' => [],
                    'revenues' => []
                ];
            } else {
                $transactions = Transactions::selectRaw('FLOOR((DAY(created_at)-1) / 10) AS period_group, DATE_FORMAT(created_at, "%b") AS month_name, YEAR(created_at) AS year, SUM(amount) AS total_revenue, SUM(fee) AS total_fee')
                                ->where('tutor_id', $user->id)
                                ->where('status', 2)
                                ->whereBetween('created_at', [$minDate, $maxDate])
                                ->groupByRaw('YEAR(created_at), MONTH(created_at), period_group')
                                ->orderByRaw('YEAR(created_at), MONTH(created_at), period_group')
                                ->get();
                $dates = [];
                $revenues = [];
                foreach($transactions as $transaction) {
                    $startDay = $transaction->period_group * 10 + 1;
                    $dates[] = $transaction->month_name . ' ' . str_pad($startDay, 2, '0', STR_PAD_LEFT);
                    $revenues[] = (int) $transaction->total_revenue - (int)$transaction->total_fee;
                }
                $data['revenues'] = [
                    'dates' => $dates,
                    'revenues' => $revenues
                ];
            }
            $bookings = Booking::where('person_id', $user->id)->with('user')->take(3)->get();
            $user->reviews = $user->reviews()->where('person_id', 0)->where('booking_id', 0)->get();
            return view('dashboard.index', compact('data', 'bookings', 'subjectTypes', 'user'));
        } else {
            $subjects = json_decode($request->user()->subject);
            $allContainTutors = User::with('reviews', 'tutor', 'package')
                ->where('role', 'tutor')
                ->where('status', 'active')
                ->whereNotNull('email_verified_at')
                ->whereNotNull('package_id')
                ->whereRaw('JSON_VALID(subject)')
                ->whereJsonContains('subject', $subjects)
                ->leftJoin('tutors', 'users.id', '=', 'tutors.user_id')
                ->leftJoin('tutor_points', 'tutors.id', '=', 'tutor_points.tutor_id')
                ->select('users.*', DB::raw('SUM(tutor_points.score) as total_score'))
                ->groupBy('users.id')
                ->orderByDesc('total_score')
                ->get();
            $atLeastOneContainTutors = User::with('reviews', 'tutor', 'package')
                ->where('role', 'tutor')
                ->where('status', 'active')
                ->whereNotNull('email_verified_at')
                ->whereNotNull('package_id')
                ->whereRaw('JSON_VALID(subject)')
                ->where(function ($query) use ($subjects) {
                    if(is_array($subjects)) {
                        foreach ($subjects as $subject) {
                            $query->orWhereJsonContains('subject', $subject);
                        }
                    }
                })
                ->leftJoin('tutors', 'users.id', '=', 'tutors.user_id')
                ->leftJoin('tutor_points', 'tutors.id', '=', 'tutor_points.tutor_id')
                ->select('users.*', DB::raw('SUM(tutor_points.score) as total_score'))
                ->groupBy('users.id')
                ->orderByDesc('total_score')
                ->get();
            $tutors = $allContainTutors->merge($atLeastOneContainTutors)->unique('id')->take(6);
            $expertiseTypes = Expertise::all()->toArray();
            $timezone = $request->user()->parent()->first()->timezone;
            return view('dashboard.tutor.index', compact('tutors', 'subjectTypes', 'expertiseTypes', 'timezone'));
        }
    }
    public function get_dashboard_data(Request $request): JsonResponse {
        $role = $request->user()->role;
        if($role === 'tutor') {
            $conversations = Conversation::where('tutor_id', $request->user()->id)->with('parent')->get()->map(function($item) {
                $item->user = $item->parent;
                unset($item->parent);
                return $item;
            });
            $bookings = Booking::with('user')->where('person_id', $request->user()->id)->where('status', 1)->get();
        } else {
            $conversations = Conversation::where('parent_id', $request->user()->id)->with('tutor')->get()->map(function($item) {
                $item->user = $item->tutor;
                unset($item->tutor);
                return $item;
            });
            $bookings = Booking::where('user_id', $request->user()->id)->where('status', 1)->get()->map(function($item) {
                $tutor = User::find($item->person_id);
                $item->user = $tutor;
                return $item;
            });
        }
        $conversationArr = [];
        foreach($conversations as $conversation) {
            $messageExists = Message::where('conversation_id', $conversation->id)->where('sender_id', '!=', $request->user()->id)->where('is_read',0)->exists();
            if($messageExists) array_push($conversationArr, $conversation);
        }
        return response()->json([
            'conversations' => $conversationArr,
            'bookings' => $bookings
        ]);
    }

    public function add_self_review(Request $request): JsonResponse {
        $request->validate([
            'clientname' => 'required',
            'rate' => 'required',
            'description' => 'required'
        ]);
        try {
            $userReview = new UserReviews([
                'user_id' => $request->user()->id,
                'person_id' => 0,
                'booking_id' => 0,
                'score' => $request->rate,
                'description' => $request->description
            ]);
            $userReview->client_name = $request->clientname;
            $userReview->save();
            $reviews = $request->user()->reviews()->where('person_id', 0)->where('booking_id', 0)->get();
            return response()->json([
                'message' => 'You leave self review successfully',
                'reviews' => $reviews
            ]);
        } catch(Exception) {
            return response()->json([
                'message' => "Review action was failed"
            ], 500);
        }
    }
    
    public function delete_self_review(Request $request, $id): JsonResponse {
        try {
            if($id) {
                UserReviews::destroy($id);
                $reviews = $request->user()->reviews()->where('person_id', 0)->where('booking_id', 0)->get();
                return response()->json([
                    'message' => 'Review was deleted successfully',
                    'reviews' => $reviews
                ], 200);
            } else {
                throw new Exception('Cannot delete the review, try again');
            }
        } catch (Exception) {
            return response()->json([
                'message' => 'Can not delete the review, try again'
            ], 500);
        }
    }
}
