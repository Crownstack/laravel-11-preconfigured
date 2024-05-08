<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using:function () {
            $wwwHost = env('WWW_HOSTNAME');
            $apiHost = env('API_HOSTNAME');
            Route::domain($wwwHost)->group(function () {
                Route::middleware('web')
                ->group(base_path('routes/web.php'));
            });
            Route::domain($apiHost)->group(function () {
                Route::middleware('api')
                ->group(base_path('routes/api.php'));
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
