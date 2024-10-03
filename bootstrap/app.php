<?php

use App\Http\Middleware\AllowLibraryAccess;
use App\Http\Middleware\CanApproveIndicators;
use App\Http\Middleware\CanSubmitIndicators;
use App\Http\Middleware\ClearSessionData;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'library' => AllowLibraryAccess::class,
            'submit' => CanSubmitIndicators::class,
            'approve' => CanApproveIndicators::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
