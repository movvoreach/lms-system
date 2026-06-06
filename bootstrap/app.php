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
        $middleware->alias([
            'two.factor' => \App\Http\Middleware\EnsureTwoFactorVerified::class,
<<<<<<< HEAD
            'telegram.action.alert' => \App\Http\Middleware\SendTelegramActionAlert::class,
=======
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'permission' => \App\Http\Middleware\EnsureUserHasPermission::class,
            'activity.requests' => \App\Http\Middleware\LogActivityRequests::class,
>>>>>>> c4098f68c864b23f4e16c45087522c4173ca4b8e
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
