<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\ValidationFailedException;

class BaseAPIValidator extends FormRequest
{
    /**
    * Failed validation disable redirect
    *
    * @param Validator $validator
    */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator);   
    }
}