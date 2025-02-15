<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DashboardMiddleware;
use App\Http\Middleware\ParentMiddleware;
use App\Http\Middleware\TutorMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']]
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(StartSession::class);
        $middleware->append(ShareErrorsFromSession::class);
        $middleware->group('api', [
            EncryptCookies::class,
            // ValidateCsrfToken::class,
            'throttle:api'
        ]);
        $middleware->group('dashboard', [
            DashboardMiddleware::class
        ]);
        $middleware->group('tutor', [
            TutorMiddleware::class
        ]);
        $middleware->group('parent', [
            ParentMiddleware::class
        ]);
        $middleware->group('admin', [
            AdminMiddleware::class
        ]);
    })
    ->withSchedule(function(Schedule $schedule) {
        $schedule->command('app:process-chat-notification')->withoutOverlapping()->everyFiveMinutes();
        $schedule->command('app:process-daily-transactions')->withoutOverlapping()->daily();
        $schedule->command('app:notify-users-without-payment-method')->withoutOverlapping()->daily();
        $schedule->command('app:daily-exchange-rate')->withoutOverlapping()->daily();
        $schedule->command('app:process-booking-status')->withoutOverlapping()->hourly();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
