<?php

namespace App\Exceptions;


use Illuminate\Http\Response;
use Throwable;
use App\Traits\ApiResponseTrait;
use Exception;

class ErrorHandler extends Exception
{
    use ApiResponseTrait;

    /**
     * Properties to hold the values of $response and $exception to make it available in the class and child classes
     */
    public  $response, $exception;  
    
    /**
     * To Override the $errorResponse from child class if any custom exception is thrown by developer.
     */
    public static $errorResponse; 

    /*
        To Store the exception response and return it while rendering the exceptions
    */
    public static $responseData; 

    /*
    **
    * To Override the exception when an non error exception is thrown for example validation exception.
    */
    public static $nonErrorExceptions;


    /*
        Application Level ERROR_CODES for default exceptions 
    */
    const ERROR_CODES = 
    [
        'ErrorException' => "ERROR_EXCEPTION",
        'ParseError' => "PARSE_ERROR",
        'TypeError' => "TYPE_ERROR",
        'Error' => "ERROR",
        'InvalidArgumentException' => "INVALID_ARGUMENT_EXCEPTION",
        'QueryException' => "QUERY_EXCEPTION",
        'BadMethodCallException' => "BAD_METHOD_CALL_EXCEPTION",
        "NotFoundHttpException" => "REQUESTED_RESOURCE_NOT_FOUND",
        "AccessDeniedHttpException" => "ACCESS_DENIED"
    ];

    /**
     * Method to return the error code, error status, and error message as a response to API  and log the response in custom format
     *
     * @param  Response $response
     * @param  Throwable $exception
     * @return array
     */
    public function throwAPIErrorResponse(Throwable $exception)
    {
        $this->exception = $exception;

        if (is_null(self::$nonErrorExceptions)) {

            self::$responseData = ($this->sendResponse(status:'error',message:$this->exception->getMessage(),errorResponseCode:$this->getAPIErrorCode()));
        }
    }
    
    /**
     * Method to return the error code of exception to the API
     *
     * @return string $errorCode
     */
    public function getAPIErrorCode()
    {

        if (!is_null(self::$errorResponse)) 
        {
            return self::$errorResponse;    
        }

        $exceptionType = class_basename($this->exception);
        if (!array_key_exists($exceptionType,self::ERROR_CODES)) 
        {
            return "SOMETHING_WENT_WRONG";
        }
        
       return self::ERROR_CODES[$exceptionType];
    }
    
    /**
     * Created method to render the API Error response 
     *
     * @return json
     */
    public function APIError()
    {
        if(! is_null(self::$responseData))
        {
            return self::$responseData;
        }

        return self::$nonErrorExceptions;
    }


}
