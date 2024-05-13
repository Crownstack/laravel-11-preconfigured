<?php

use App\Exceptions\ErrorHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
                        
            /** 
             * To access the application from web we will use the $wwwHost host.
             * @var string $wwwHost 
             * 
             * */
            $wwwHost = env('WWW_HOSTNAME');         
               
            /** 
             * To access the application using API we will use $apiHost. 
             * @var mixed $apiHost 
             * */
            $apiHost = env('API_HOSTNAME');    

            /** 
             * Files to define the web routes with web middleware 
             * @var mixed $wwwHost */
            Route::domain($wwwHost)->group(function () {
                Route::middleware('web')
                    ->group(base_path('routes/web.php'));
            }); 

            /** 
             * File to write the API routes with API middleware
             * @var mixed $apiHost */
            Route::domain($apiHost)->group(function () {
                Route::middleware('api')->prefix('api')
                    ->group(base_path('routes/api.php'));
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /**
         * To override the exception returned by laravel we have to manage three thing first is to report exception in 
         * custom format then disable the default JSON returned by Laravel and the respond the Exception in custom format 
         * 
         * API_ERROR_REPORTING to manage the be
         */
        
        if (request()->is('api/*') && env('API_ERROR_REPORTING') == false) {
                        
            /**
             *  @var mixed $exceptions- 
             * To report exception in custom format for API 
             * */
            $exceptions->report(function (Throwable $exception) {
                return (new ErrorHandler)->throwAPIErrorResponse($exception);               
            });
            
            /** 
             * @var mixed $exceptions- 
             * Laravel returns the exception in JSON format for API so overriding the behavior to implement the Custom JSON response
             * */
            $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e)
            {
                return false;
            });
            
            /** @var mixed $exceptions- 
             * Responding the API exception in custom Format  
             * 
            */
            $exceptions->respond(function (Response $response) {
                return (new ErrorHandler)->APIError();
            });
        }

    })->create();
