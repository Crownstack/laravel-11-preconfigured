<?php

namespace App\Services\Communications\Email;

use App\Jobs\EmailSenderJob;
use Illuminate\Support\Facades\Mail;
use App\Traits\ModelResponseTrait;
use Illuminate\Contracts\Mail\Mailable;
use App\Services\Communications\BaseMailable;
use Exception;
use App\Traits\EmailTrait;

class SMTP  extends BaseMailable 
{
    use ModelResponseTrait;

    public $data;
    

    /**
     * Create a new class instance.
     */
    public function __construct($data)
    {
        if (!isset($data['to']) || !isset($data['subject']) || !isset($data['body'])) 
        {
            throw new Exception("To, Subject, and Body is required to send an email ");
        }

        $this->header = $data['header'] ?? '';
        $this->body = $data['body'] ?? '';
        $this->footer = $data['footer'] ?? '';
        $this->subject = $data['subject'] ?? '';
        
        $data['cc'] = $data['cc'] ?? [];
        $data['bcc'] = $data['bcc'] ?? [];      
        $this->attachments = $data['attachments'] ?? [];      

        $this->data = $data;
    }
    
    /**
     * Method to send email and used as the common method in all kind of service provider.
     *
     * @param  array $data
     * 
     * @return array $response
     */
    public function sendEmail($async = false)
    {
        if($async)
        {
            $this->sendAsyncEmail();
            return $this->returnData('ok', 'email_sent_successfully');
        }
        
        return $this->triggerEmail();
    }

    
    /**
     * Method to dispatch a Job to send asynchronous emails.
     *
     * @return void
     */
    public function sendAsyncEmail()
    {
        $data = $this->data;
        $message = $this->build();

        EmailSenderJob::dispatch($data, $message);
    }

    
    /**
     * Method to set the mail parameters and send email
     *
     * @return array $response
     */
    public function triggerEmail()
    {
        $data = $this->data;

        Mail::to($data['to'])->cc($data['cc'])->bcc($data['bcc'])->send($this->build());

        return $this->returnData('ok', 'email_sent_successfully');
    }
}
