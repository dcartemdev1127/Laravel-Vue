<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transactions;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Stripe\Refund;
use Stripe\Stripe;

class ProcessBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-booking-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $bookings = Booking::where('status', 1)->get();
            foreach($bookings as $booking) {
                $start_date = $booking->start_date;
                $start_time = $booking->start_time;
                $timezone = $booking->timezone;
                if($start_time) {
                    $start_datetime = Carbon::createFromFormat('d-m-Y H:i:s', $start_date . " " . $start_time, $timezone);
                } else {
                    $start_datetime = Carbon::createFromFormat('d-m-Y', $start_date, $timezone);
                }
                $current_time = Carbon::now($timezone);
                $differenceInMinutes = $current_time->diffInMinutes($start_datetime);
                if($differenceInMinutes <= 60) {
                    if($booking->payment_intent_id) {
                        Stripe::setApiKey(config('stripe.secret'));
                        $refund = Refund::create([
                            'payment_intent' => $booking->payment_intent_id
                        ]);
                        if($refund->status !== 'succeeded') {
                            return response()->json([
                                'message' => 'Failed to refund the payment'
                            ], 500);
                        }
                    }
                    $booking->status = 0;
                    $booking->save();
                    Transactions::where('booking_id', $booking->id)->update(['status' => 0]);
                }
            }
        } catch(Exception $e) {
            Log::error("ProcessBookingStatus Error: " . $e->getMessage());
        }
        try {
            $bookings = Booking::where('status', 2)->get();
            foreach($bookings as $booking) {
                $start_date = $booking->start_date;
                $start_time = $booking->start_time;
                $timezone = $booking->timezone;
                if($start_time) {
                    $start_datetime = Carbon::createFromFormat('d-m-Y H:i:s', $start_date . " " . $start_time, $timezone);
                } else {
                    $start_datetime = Carbon::createFromFormat('d-m-Y', $start_date, $timezone);
                }
                $current_time = Carbon::now($timezone);
                $differenceInHours = $current_time->diffInHours($start_datetime);
                if($differenceInHours <= -24) {
                    $booking->status = 3;
                    $booking->save();
                    Transactions::where('booking_id', $booking->id)->update([
                        'status' => 2,
                        'completed_at' => Carbon::now()
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("ProcessBookingStatus Error: " . $e->getMessage());
        }
    }
}
