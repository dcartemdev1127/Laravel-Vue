<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StpEarnNotification;
use App\Models\Booking;
use App\Models\Expertise;
use App\Models\Language;
use App\Models\Message;
use App\Models\Package;
use App\Models\Subject;
use App\Models\SubjectItem;
use App\Models\Tutor_points;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use PhpParser\Node\Expr\Cast\Bool_;

class UserManageController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = "/";

    public function index()
    {
        return redirect('/');
    }
    public function showCahtUsers(Request $request): View {
        $tutors = User::with('conversationAsTutor.parent')->where('role', 'tutor')->get();
        return view('admin.users.chats', compact('tutors'));
    }
    public function getChatContent(Request $request, $id): JsonResponse {
        try {
            if(!$id) throw new Exception("Can't load message content");
            $messages = Message::where('conversation_id', $id)->orderBy('created_at')->get();
            return response()->json([
                'message' => 'success',
                'messages' => $messages
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function showBookings(Request $request): View {
        $bookings = Booking::with('user', 'transactions')->get()->map(function($booking) {
            $tutor = User::find($booking->person_id);
            $booking->tutor = $tutor;
            return $booking;
        });
        $subjects = Subject::all('id', 'name');
        $subjectTypes = [];
        foreach($subjects as $subject) {
            array_push($subjectTypes, [
                'id' => 0,
                'name' => $subject->name,
                'selectable' => false
            ]);
            foreach($subject->subjectItems()->orderBy('order_by', 'desc')->get() as $subjectItem) {
                array_push($subjectTypes, [
                    'id' => $subjectItem->id,
                    'name' => $subjectItem->name,
                    'selectable' => true
                ]);
            }
        }
        return view('admin.users.bookings', compact('bookings', 'subjectTypes'));
    }
    public function showBookingDetails(Request $request, $id): View {
        $booking = Booking::with('user', 'transactions')->find($id);
        if(!$booking) {
            abort(404);
        }
        $tutor = User::find($booking->person_id);
        $booking->tutor = $tutor;
        $subjects = Subject::all('id', 'name');
        $subjectTypes = [];
        foreach($subjects as $subject) {
            array_push($subjectTypes, [
                'id' => 0,
                'name' => $subject->name,
                'selectable' => false
            ]);
            foreach($subject->subjectItems()->orderBy('order_by', 'desc')->get() as $subjectItem) {
                array_push($subjectTypes, [
                    'id' => $subjectItem->id,
                    'name' => $subjectItem->name,
                    'selectable' => true
                ]);
            }
        }
        return view('admin.users.bookingdetails', compact('booking', 'subjectTypes'));
    }
    public function showTutors(Request $request): View
    {
        $subjects = Subject::all('id', 'name');
        $subjectTypes = [];
        foreach($subjects as $subject) {
            array_push($subjectTypes, [
                'id' => 0,
                'name' => $subject->name,
                'selectable' => false
            ]);
            foreach($subject->subjectItems()->orderBy('order_by', 'desc')->get() as $subjectItem) {
                array_push($subjectTypes, [
                    'id' => $subjectItem->id,
                    'name' => $subjectItem->name,
                    'selectable' => true
                ]);
            }
        }
        $tutors = User::where('role', 'tutor')->with('tutor')->get()->map(function($tutor) {
            $tutor->package = Package::find($tutor->package_id);
            return $tutor;
        });
       
        return view('admin.users.tutors', compact('tutors', 'subjectTypes'));
    }
    public function deleteUser(Request $request, $id):JsonResponse {
        $user = User::find($id);
        try {
            if($user->role === 'tutor') {
                $user->tutorBookings()->delete();
                $user->conversationAsTutor()->delete();
                if($user->tutor()->first()) {
                    $user->tutor()->first()->transactions()->delete();
                }
                $user->delete();
                $tutors = User::where('role', 'tutor')->with('tutor')->get()->map(function($tutor) {
                    $tutor->package = Package::find($tutor->package_id);
                    return $tutor;
                });
                return response()->json([
                    'message' => 'User was deleted successfully',
                    'tutors' => $tutors
                ]);
            } else {
                $user->conversationAsParent()->delete();
                if($user->parent()->first()) {
                    $user->parent()->first()->transactions()->delete();
                }
                $user->delete();
                $parents = User::where('role', 'parent')->with('parent')->get();
                return response()->json([
                    'message' => 'User was deleted successfully',
                    'parents' => $parents
                ]);
            }
        } catch(Exception $e) {
            var_dump($e->getMessage());
            die();
            return response()->json([
                'message' => 'Delete action was failed'
            ], 500);
        }
    }
    public function showUserProfile(Request $request, $id):View {
        $profileUser = User::with('reviews.person')->find($id);
        if($profileUser->role === 'tutor') {
            $profileUser->user = $profileUser->tutor()->first();
            $profileUser->scores = $profileUser->tutor()->first()->points()->get();
            $profileUser->students = $profileUser->tutorBookings()->whereIn('status', [2,3,4,5])->distinct()->count('user_id');
        }
        else {
            $profileUser->user = $profileUser->parent()->first();
        }
        $subjectTypes = SubjectItem::all()->toArray();
        $expertiseTypes = Expertise::all()->toArray();
        return view('admin.users.profile', compact('profileUser', 'subjectTypes', 'expertiseTypes'));
    }
    public function AddScore(Request $request):JsonResponse {
        $request->validate([
            'id' => 'required',
            'score' => 'required'
        ]);
        try {
            $user = User::with('reviews')->findOrFail($request->id);
            $newPoints = new Tutor_points([
                'tutor_id' => $user->tutor()->first()->id,
                'score' => $request->score
            ]);
            $newPoints->reason = 'by admin';
            $newPoints->save();
            Mail::to($user->email)->send(new StpEarnNotification($user->firstname . " " . $user->lastname, $request->score));
            $user->user = $user->tutor()->first();
            $user->scores = $user->tutor()->first()->points()->get();
            return response()->json([
                'message' => 'STP was added successfully',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function RemoveScore(Request $request): JsonResponse {
        $request->validate([
            'user_id' => 'required',
            'score_id' => 'required'
        ]);
        try {
            $score = Tutor_points::find($request->score_id);
            $user = User::with('reviews')->find($request->user_id);
            $tutor = $user->tutor()->first();
            if(!$score) {
                return response()->json([
                    'message' => 'The score is not exists'
                ], 500);
            }
            if(intval($score->tutor_id) !== intval($tutor->id)) {
                return response()->json([
                    'message' => "The score can't be deleted"
                ], 500);
            }
            $score->delete();
            $user->user = $tutor;
            $user->scores = $tutor->points()->get();
            return response()->json([
                'message' => 'STP was removed successfully',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function ToggleStatus(Request $request):JsonResponse {
        $request->validate([
            'id' => 'required',
            'status' => 'required'
        ]);
        try {
            $user = User::with('reviews')->findOrFail($request->id);
            $message = 'The user was set to active status';
            if($request->status === 'active') {
                $user->status = 'inactive';
                $message = 'The user was set to inactive status';
            } else {
                $user->status = 'active';
            }
            $user->save();
            if($user->role === 'tutor') {
                $user->user = $user->tutor()->first();
                $user->scores = $user->tutor()->first()->points()->get();
                $user->students = $user->tutorBookings()->whereIn('status', [2,3,4,5])->distinct()->count('user_id');
            } else {
                $user->user = $user->parent()->first();
            }
            return response()->json([
                'message' => $message,
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function showParents(Request $request): View
    {
        $subjects = Subject::all('id', 'name');
        $subjectTypes = [];
        foreach($subjects as $subject) {
            array_push($subjectTypes, [
                'id' => 0,
                'name' => $subject->name,
                'selectable' => false
            ]);
            foreach($subject->subjectItems()->orderBy('order_by', 'desc')->get() as $subjectItem) {
                array_push($subjectTypes, [
                    'id' => $subjectItem->id,
                    'name' => $subjectItem->name,
                    'selectable' => true
                ]);
            }
        }
        $parents = User::where('role', 'parent')->with('parent')->get();
        return view('admin.users.parents', compact('parents', 'subjectTypes'));
    }
    public function showCategories(Request $request): View
    {
        $languages = Language::all()->toArray();
        $subjectTypes = $this->getSubjectTypes();
        $expertises = Expertise::all()->toArray();
        return view('admin.users.categories', compact('languages', 'subjectTypes', 'expertises'));
    }
    public function addLanguage(Request $request): JsonResponse {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            $data = $request->only('name');
            $newLanguage = new Language($data);
            $newLanguage->save();
            $languages = Language::all()->toArray();
            return response()->json([
                'message' => 'New language was added successfully',
                'languages' => $languages
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteLanguage(Request $request, $id) : JsonResponse {
        try {
            $language = Language::find($id);
            $language->delete();
            $languages = Language::all()->toArray();
            return response()->json([
                'message' => 'Language was removed successfully',
                'languages' => $languages
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function addSubjectCategory(Request $request): JsonResponse {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            $data = $request->only('name');
            $newSubject = new Subject($data);
            $newSubject->save();
            $subjectTypes = $this->getSubjectTypes();
            return response()->json([
                'message' => 'Subject category was added successfully',
                'subjectTypes' => $subjectTypes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function addSubjectItem(Request $request): JsonResponse {
        $request->validate([
            'subject_id' => 'required',
            'name' => 'required',
            'order_by' => 'required'
        ]);
        try {
            $data = $request->only('subject_id', 'name', 'order_by');
            $newSubjectItem = new SubjectItem($data);
            $newSubjectItem->save();
            $subjectTypes = $this->getSubjectTypes();
            return response()->json([
                'message' => 'Subject item was added successfully',
                'subjectTypes' => $subjectTypes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteSubjectCategory(Request $request, $id): JsonResponse {
        try {
            $subject = Subject::find($id);
            $subject->delete();
            $subjectTypes = $this->getSubjectTypes();
            return response()->json([
                'message' => 'Subject category was removed successfully',
                'subjectTypes' => $subjectTypes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteSubjectItem(Request $request, $id): JsonResponse {
        try {
            $subjectItem = SubjectItem::find($id);
            $subjectItem->delete();
            $subjectTypes = $this->getSubjectTypes();
            return response()->json([
                'message' => 'Subject item was removed successfully',
                'subjectTypes' => $subjectTypes
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function addExpertise(Request $request): JsonResponse {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            $data = $request->only('name');
            $newExpertise = new Expertise($data);
            $newExpertise->save();
            $expertises = Expertise::all()->toArray();
            return response()->json([
                'message' => 'Expertise item was added successfully',
                'expertises' => $expertises
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteExpertise(Request $request, $id): JsonResponse {
        try {
            $expertise = Expertise::find($id);
            $expertise->delete();
            $expertises = Expertise::all()->toArray();
            return response()->json([
                'message' => 'Expertise item was removed successfully',
                'expertises' => $expertises
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    protected function getSubjectTypes() {
        $subjects = Subject::all('id', 'name');
        $subjectTypes = [];
        foreach($subjects as $subject) {
            array_push($subjectTypes, [
                'id' => $subject->id,
                'name' => $subject->name,
                'selectable' => false
            ]);
            foreach($subject->subjectItems()->orderBy('order_by', 'desc')->orderBy('id')->get() as $subjectItem) {
                array_push($subjectTypes, [
                    'id' => $subjectItem->id,
                    'subject_id' => $subject->id,
                    'name' => $subjectItem->name,
                    'selectable' => true
                ]);
            }
        }
        return $subjectTypes;
    }
}
