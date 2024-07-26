<?php
    /*
    * Communication Channels
    * This file will store the communication channels used for sending Email,SMS etc..
    */
    
    return [
        'sms' => [
            'twilio' => App\Services\Communications\SMS\Twilio::class,
        ],
        'email' => [
            'smtp' => App\Services\Communications\Email\SMTP::class,
        ]

    ];
