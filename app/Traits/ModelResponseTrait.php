<?php

namespace App\Traits;

trait ModelResponseTrait
{

    public function returnData($responseType = 'error', $responseCode = 'empty', $data = [])
    {
        return[
            'responseType' => $responseType,
            'responseCode' => $responseCode,
            'data' => $data
        ];

    }
}
