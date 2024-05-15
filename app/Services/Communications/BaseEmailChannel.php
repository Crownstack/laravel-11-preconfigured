<?php

namespace App\Services\Communications;

use App\Services\Communications\BaseCommunicationService;

abstract class BaseEmailChannel extends BaseCommunicationService
{
    
    /**
     * Method to send email 
     *
     * @param  array $data
     * @return void
     */
    abstract public function sendEmail($data);
    
}
