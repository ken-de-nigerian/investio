<?php

use App\Http\Middleware\OnboardingCheck;
use App\Http\Middleware\PreventIfKYCExists;
use App\Http\Middleware\ProfileComplete;
use App\Http\Middleware\RedirectAuthenticated;
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
        // Middleware aliases
        $middleware->alias([
            'kyc.check' => PreventIfKYCExists::class,
            'onboarding.check' => OnboardingCheck::class,
            'profile.complete' => ProfileComplete::class,
            'redirect.authenticated' => RedirectAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
