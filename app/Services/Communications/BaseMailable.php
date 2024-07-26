<?php

namespace App\Services\Communications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class BaseMailable extends Mailable
{
	use Queueable, SerializesModels;

    public $header,$body,$footer,$subject,$attachments;
	public $emailTemplateLayout = 'mails.mail';
	/*
	* This single method is responsible to:
	* 1. Render the required view to prepare email body
	* 2. Set email's subject
	* 3. Set From email and name
	* 4. Attach attachments if given in mail settings
	*/
	public function build()
	{
        $header = $this->header;
        $body = $this->body;
        $footer = $this->footer;

		$mailableObject = $this->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"))
			->subject(trans($this->subject))
			->view($this->emailTemplateLayout,compact('header','body','footer'));

		if(!empty($attachments))
		{
			foreach($this->attachments as $attachment)
			{
				$name = [];

				if(isset($attachment['name']))
				{
					$name =  ['as' => $attachment['name']];
				}

				$mailableObject = $mailableObject->attach($attachment['path'],$name);
			}
		}

		return($mailableObject);
	}

	abstract function sendEmail();

}
