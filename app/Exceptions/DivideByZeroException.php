<?php

namespace App\Exceptions;

use App\Exceptions\ErrorHandler;
/**
 * Custom Exception to throw DivideByZeroException according to new structure
 */
class DivideByZeroException extends ErrorHandler
{
    public $message;


    public function __construct($message)
    {
        $this->message = $message;
        self::$errorResponse = "CANNOT_DIVIDE_BY_ZERO";

    }

}
