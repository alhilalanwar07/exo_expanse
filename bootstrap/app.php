<?php

use App\Http\Middleware\AddStrictTransportSecurityHeader;
use App\Http\Middleware\AuthenticateMobileSession;
use App\Http\Middleware\ForceHttpsInProduction;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->cors(
            paths: ['api/*', 'sanctum/csrf-cookie'],
            allowedMethods: ['*'],
            allowedOrigins: ['*'], // Izinkan semua untuk mobile development
            allowedHeaders: ['*'],
            exposedHeaders: [],
            maxAge: 0,
            supportsCredentials: false
        );

        $middleware->append([
            ForceHttpsInProduction::class,
            AddStrictTransportSecurityHeader::class,
        ]);

        $middleware->alias([
            'mobile.session' => AuthenticateMobileSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
