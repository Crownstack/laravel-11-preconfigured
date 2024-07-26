<?php

use App\Services\Communications\Email\SMTP;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


/**
 *   To send the testing  email 
 *   Route::get('/email-test',function(){
 *   
 *       $data = [
 *           'to' => 'abhishek.jha@crownstack.com',
 *           'subject' => 'Async Email Testing',
 *           'body' => 'This is a test email sent using async email feature in Laravel 8'  
 *       ];
 *   
 *       (new SMTP($data))->sendEmail(async:true);
 *   
 *   });
 */