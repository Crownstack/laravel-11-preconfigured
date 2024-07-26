<?php

use Illuminate\Support\Facades\Route;

/**All routes starts with v1/  */
Route::group(['prefix' => 'v1'], function () {

    /**All routes starts with v1/users  */
    Route::group(['prefix' => 'users'], function () {
        /**
         * POST 		/v1/users/signup
         * 
         * API for user signup.
         */
        Route::post('signup', [App\Http\Controllers\Api\V1\UserController::class, 'signup']);
    });
});
