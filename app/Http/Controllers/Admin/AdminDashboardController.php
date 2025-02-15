<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInbox;
use App\Models\Booking;
use App\Models\Expertise;
use App\Models\Package;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

class AdminDashboardController extends Controller
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
    public function showDashboard(Request $request): View
    {
        $data = array();
        $data['totalUsers'] = User::where('role', '!=', 'admin')->count();
        $data['newUsers'] = User::where('role', '!=', 'admin')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $data['activeUsers'] = User::where('role', '!=', 'admin')->whereNotNull('email_verified_at')->count();
        $data['activeTutors'] = User::where('role', 'tutor')->whereNotNull('email_verified_at')->count();
        $data['activeParents'] = User::where('role', 'parent')->whereNotNull('email_verified_at')->count();
        $data['topTutors'] = User::where('role', 'tutor')->whereHas('tutor')->with('tutor', 'reviews')->take(4)->get();
        $data['totalBookings'] = Booking::all()->count();
        $data['totalRevenue'] = $this->calculateRevenue();
        $data['pendingRevenue'] = Transactions::where('status', 1)->sum('fee');
        $data['inbox'] = AdminInbox::where('status', 1)->get();
        $minDate = Transactions::min('created_at');
        $maxDate = Transactions::max('created_at');
        if(!$minDate || !$maxDate) {
            $data['revenues'] = [
                'dates' => [],
                'revenues' => []
            ];
        } else {
            $transactions = Transactions::selectRaw('FLOOR((DAY(created_at)-1) / 10) AS period_group, DATE_FORMAT(created_at, "%b") AS month_name, YEAR(created_at) AS year, SUM(amount) AS total_revenue')
                            ->whereBetween('created_at', [$minDate, $maxDate])
                            ->groupByRaw('YEAR(created_at), MONTH(created_at), period_group')
                            ->orderByRaw('YEAR(created_at), MONTH(created_at), period_group')
                            ->get();
            $dates = [];
            $revenues = [];
            foreach($transactions as $transaction) {
                $startDay = $transaction->period_group * 10 + 1;
                $dates[] = $transaction->month_name . ' ' . str_pad($startDay, 2, '0', STR_PAD_LEFT);
                $revenues[] = (int) $transaction->total_revenue;
            }
            $data['revenues'] = [
                'dates' => $dates,
                'revenues' => $revenues
            ];
        }
        $subjectTypes = getSubjectTypes();
        $expertiseTypes = Expertise::all()->toArray();
        return view('admin.dashboard', compact('data', 'subjectTypes', 'expertiseTypes'));
    }

    public function calculateRevenue() {
        $transactionRevenue = Transactions::sum('fee');
        $subscriptionRevenue = 0;
        $subscriptionArr = [];
        $subscriptionItems = SubscriptionItem::all();
        foreach($subscriptionItems as $item) {
            $trial_ends_at = Subscription::find($item->subscription_id)->trial_ends_at;
            if($item->created_at > $trial_ends_at) {
                if(isset($subscriptionArr[$item->stripe_price])) $subscriptionArr[$item->stripe_price] += intval($item->quantity);
                else $subscriptionArr[$item->stripe_price] = intval($item->quantity);
            }
        }
        foreach($subscriptionArr as $key => $value) {
            $package = Package::where('monthly_price_id', $key)->first();
            if($package) {
                $subscriptionRevenue += intval($package->monthly_price) * $value;
            } else {
                $package = Package::where('yearly_price_id', $key)->first();
                $subscriptionRevenue += intval($package->yearly_price) * $value;
            }
        }
        return $subscriptionRevenue + $transactionRevenue;
    }
}
