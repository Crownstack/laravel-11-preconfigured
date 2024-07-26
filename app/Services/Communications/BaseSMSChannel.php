<?php

namespace App\Services\Communications;

use App\Services\Communications\BaseCommunicationService;

abstract class BaseSMSChannel extends BaseCommunicationService
{    
    /**
     * Method to be implemented by communication channels to send single message
     *
     * @param  array $data
     * Example structure of $data
     *  [
     *      'to' => [] array of numbers with country code,
     *      'message' => '' 
     *  ]
     * @param  bool $sync
     * @return array
     */
    abstract public function sendSMS($data,$sync);
    
    /**
     * Method to be implemented by communication channels to send bulk message
     *
     * @param  array $data
     * @param  bool $sync
     * @return array
     */
    abstract function sendBulkSMS($data,$sync);
    
}
