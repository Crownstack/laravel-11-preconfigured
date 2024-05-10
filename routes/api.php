<?php

use Illuminate\Support\Facades\Route;
use App\Exceptions\DivideByZeroException;

Route::group(['prefix'=>'v1'], function(){


    // Route::post('error-exceptions',function(){
    //     $b ='new';
        
    //     // try {
    //     //     $val = 100/0;
    //         throw new DivideByZeroException("Divide by zero ");
    //     // } catch ( $th) {
    //     // }
    //     // view('abcd');
    //     // dd(DB::table('users')->get()->contacts());
    //     dd('called');
    // });
});
