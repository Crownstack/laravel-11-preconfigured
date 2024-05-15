<?php

namespace App\Services\Communications\Email;

use App\Services\Communications\BaseEmailChannel;
use App\Jobs\EmailSenderJob;
use Illuminate\Support\Facades\Mail;
use App\Traits\ModelResponseTrait;

class SMTP extends BaseEmailChannel
{
    use ModelResponseTrait;

    public $preparedMail;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendEmail($data)
    {

    }


    public function sendAsyncEmail()
    {
        // EmailSenderJob::dispatch($receivers, $message);
    }


    public function triggerEmail($data)
    {
        Mail::to($data['to'])
            ->cc($data['cc'])
            ->bcc($data['bcc'])
            ->send($data['preparedMail']);

        return $this->returnData('ok', 'email_sent_successfully');
    }
}
