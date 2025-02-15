<?php

namespace App\Console\Commands;

use App\Mail\BookingTransactionFail;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Service;
use App\Models\Transactions;
use App\Models\User;

use Stripe\Stripe;
use Stripe\PaymentIntent;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;

class ProcessDailyTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-daily-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process termly, yearly transactions to handle payment weekly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing transactions');
        $bookings = Booking::whereIn('contract_method', ['termly', 'yearly'])->where('status', 2)->get();
        foreach($bookings as $booking) {
            if($booking->contract_method === 'termly' && intval($booking->paid_times) >= 10) continue;
            if($booking->contract_method === 'yearly' && intval($booking->paid_times) >= 52) continue;
            $currentDate = Carbon::now()->format('d-m-Y');
            $paidDateStr = $booking->paid_date ? $booking->paid_date : $booking->start_date;
            $paidDate = Carbon::parse($paidDateStr);
            $days = $paidDate->diffInDays($currentDate,false);
            if($days >= 7) {
                $parent = User::with('parent')->find($booking->user_id);
                $tutor = User::with('tutor')->find($booking->person_id);
                $real_currency = '';
                $package = Package::find($tutor->package_id);
                $fee = floatval($package->percent) / 100.0;
                if($booking->service_id) {
                    $service = Service::find($booking->service_id);
                    $real_currency = $service->currency;
                    $currency = $real_currency === '$' ? 'USD' : ($real_currency === '€' ? 'EUR' : 'GBP');
                    if($booking->contract_method === 'termly') {
                        $amount = floatval($service->termlyrate / 10.0);
                        $feeAmount = $amount * $fee;
                        $payAmount = $amount + $feeAmount;
                    } else {
                        $amount = floatval($service->annualrate / 52.0);
                    }
                } else {
                    $real_currency = $tutor->tutor->currency;
                    $currency = $real_currency === '$' ? 'USD' : ($real_currency === '€' ? 'EUR' : 'GBP');
                    if($booking->contract_method === 'termly') {
                        $amount = floatval($tutor->tutor->termlyrate / 10.0);
                        $feeAmount = $amount * $fee;
                        $payAmount = $amount + $feeAmount;
                    } else {
                        $amount = floatval($tutor->tutor->annualrate / 52.0);
                        $feeAmount = $amount * $fee;
                        $payAmount = $amount + $feeAmount;
                    }
                }
                try {
                    Stripe::setApiKey(config('stripe.secret'));
                    $paymentIntent = PaymentIntent::create([
                        'amount' => ceil($payAmount * 100),
                        'currency' => $currency,
                        'customer' => $parent->stripe_id,
                        'payment_method' => $parent->defaultPaymentMethod()->id,
                        'automatic_payment_methods' => [
                            'enabled' => true,
                            'allow_redirects' => 'never'
                        ],
                        'confirm' => true
                    ]);
                    if($paymentIntent->status !== 'succeeded') {
                        return response()->json([
                            'message' => 'Your Payment information is not correct'
                        ], 500);
                    }
                } catch (Exception $e) {
                    Mail::to($parent->email)->send(new BookingTransactionFail($parent, $booking));
                    Mail::to($tutor->email)->send(new BookingTransactionFail($tutor, $booking));
                    Log::error($e->getMessage());
                    die();
                }
                try {
                    $booking->paid_date = $currentDate;
                    $booking->paid_times = intval($booking->paid_times) + 1;
                    $booking->save();
                    
                    $service_name = empty($booking->service_id) ? 'User Booking' : Service::find($booking->service_id)->title;
                    $newTransaction = new Transactions([
                        'tutor_id' => $booking->person_id,
                        'parent_id' => $booking->user_id,
                        'tutor_name' => $tutor->firstname,
                        'client_name' => $parent->firstname,
                        'booking_id' => $booking->id,
                        'service' => $service_name,
                        'currency' => $real_currency,
                        'amount' => $amount,
                        'fee' => $feeAmount,
                        'method' => 'Stripe'
                    ]);
                    $newTransaction->completed_at = Carbon::now();
                    $newTransaction->save();
                } catch(Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
