<?php

namespace App\Services\Communications\SMS;

use App\Services\Communications\BaseSMSChannel;
use App\Traits\ModelResponseTrait;
use Twilio\Rest\Client;
use App\Jobs\SMSSenderJob;

class Twilio extends BaseSMSChannel
{
    use ModelResponseTrait;
    public $twilioNumber;
    public $twilioClient;

    
    /**
     * Creating an object of Twilio class 
     *
     * @return void
     */
    public function __construct()
    {
        $clientId = env('TWILIO_ACCOUNT_SID');
        $clientSecrete = env('TWILIO_AUTH_TOKEN');
        $this->twilioNumber = env('TWILIO_FROM_NUMBER');

        $this->twilioClient = new Client($clientId, $clientSecrete);
    }

    
    /**
     * Method to send sync or async SMS 
     *
     * @param  array $data
     * @param  bool $sync
     * @param  int $delay in minutes
     * @return array
     */
    public function sendSMS($data, $sync = true, $delay = 0)
    {
        if (!$sync) 
        {
            return $this->sendAsyncSMS($data['to'],$data['message'] , $delay);
        }  
        
        if (count($data['to']) > 1) 
        {
            return $this->sendBulkSMS($data['to'],$data['message'],$sync,$delay);
        }

        return  $this->triggerMessage($data['to'][0],$data['message']);
    }
    
    /**
     * Method to send bulk sms from Twilio
     *
     * @param  array $to
     * @param  string $message
     * @param  bool $sync
     * @param  int $delay in minutes
     * @return void
     */
    public function sendBulkSMS($to, $message, $sync = true, $delay = 0)
    {
        if ($sync) {

            foreach ($to as  $number) {
                $this->triggerMessage($number, $message);
            }
            return [];
        }
        return $this->sendAsyncSMS($to,$message,$delay);
    }

    
    /**
     * Method to send SMS asynchronously using SMSSenderJob Job
     *
     * @param  array $to
     * @param  string $message
     * @param  int $delay
     * @return void
     */
    public function sendAsyncSMS($to, $message , $delay = 0)
    {
        if ($delay > 0) 
        {
            SMSSenderJob::dispatch($to,$message,$this)->delay(now()->addMinute($delay));
            return $this->returnData('ok', 'sms_sending_in_process');
        }

        SMSSenderJob::dispatch($to,$message,$this);
        return $this->returnData('ok', 'sms_sending_in_process');

    }
    
    /**
     * Method to send SMS from Twilio
     *
     * @param  string $to
     * @param  string $message
     * @return void
     */
    public function triggerMessage($to,$message)
    {
        try
		{
			$result = $this->twilioClient->messages->create(
				$to,
				[
					'from' => $this->twilioNumber,
					'body' => trans($message)
				]
			);

			if(!is_null($result->sid))
			{
				return $this->returnData('ok', 'sms_sent');
			}

			return $this->returnData('error', 'sms_not_sent', []);
			
		}
		catch(\Twilio\Exceptions\RestException $e)
		{
			$twilio_exception_info = [
				'error_code' => $e->getCode(),
				'error_message' => $e->getMessage()
			];
			return $this->returnData('error', 'sms_not_sent', $twilio_exception_info);
		}
    }
}
