<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));

        $middleware->alias([
            'two.factor' => \App\Http\Middleware\EnsureTwoFactorVerified::class,
            'activity.requests' => \App\Http\Middleware\LogActivityRequests::class,
            'telegram.action.alert' => \App\Http\Middleware\SendTelegramActionAlert::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
