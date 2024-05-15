<?php

namespace App\Services\Communications;

use Exception;
use Illuminate\Events\Dispatcher;
use App\Events\UserSignup;
use Illuminate\Support\Facades\Log;

class BaseCommunicationService
{
    
    /**
     * Method to subscribe event which will use notification 
     *
     * @param  Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {

        $events->listen(UserSignup::class, [BaseCommunicationService::class, 'userSignedUp']);
    }

    /**
     * Load vendor according for the selected communication channel 
     *
     * @param  mixed $channel sms/email
     * @param  mixed $vendor
     * @return object
     */
    public function loadChannelVendor(string $channel, string $vendor)
    {
        $communicationChannels = config('communicationChannels');

        if (!array_key_exists($channel, $communicationChannels))  
        {
            throw new Exception("Invalid channel");
        }

        $channelVendors = $communicationChannels[$channel];


        if (!array_key_exists($vendor, $channelVendors)) {
            throw new Exception("Invalid vendor");
        }

        $channelVendor = $channelVendors[$vendor];

        return new $channelVendor;
    }
    
    /**
     * Method to send SMS after user signup
     *
     * @param  UserSignup $input
     * @return array
     */
    public function userSignedUp($input)
    {
        $user = $input->user;
        $data = [
            'to' => [$user->phone_number],
            'message' => "Hello $user->name, Signup successful !! "
        ];
        $smsVendor = $this->loadChannelVendor('sms', 'twilio');
        return $smsVendor->sendSMS($data, sync: true);
    }
}
