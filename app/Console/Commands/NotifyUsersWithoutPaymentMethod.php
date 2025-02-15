<?php

namespace App\Console\Commands;

use App\Mail\NotifyPaymentMethod;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyUsersWithoutPaymentMethod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-users-without-payment-method';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users with trial periods ending and no payment method set';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where(function($query) {
                $query->whereNull('stripe_id')->orWhere('stripe_id', '');
            })->whereHas('subscriptions', function($query) {
                $query->where('stripe_status', 'trialing')->where('trial_ends_at', '<=', now()->addDays(10));
            })->get();
        foreach($users as $user) {
            Mail::to($user->email)->send(new NotifyPaymentMethod($user->firstname));
        }
    }
}
