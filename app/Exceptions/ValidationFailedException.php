<?php

namespace App\Exceptions;

use App\Exceptions\ErrorHandler;


class ValidationFailedException extends ErrorHandler
{

    public $validator;
    public $errorResponseCode = 'REQUEST_VALIDATION_ERROR';
    public function __construct($validator)
    {
        $this->validator = $validator;
        self::$errorResponse  = 'REQUEST_VALIDATION_ERROR';
        $failedValidationMessages = $this->validator->messages()->toArray();
        self::$nonErrorExceptions = $this->sendResponse(status:'error',message:'Validation failed',data:$failedValidationMessages,statusCode:422,errorResponseCode:$this->errorResponseCode);
    }

}
