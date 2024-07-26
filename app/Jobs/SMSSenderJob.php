<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SMSSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $numbers;
    public $message;
    public $vendor;

    /**
     * Create a new instance of SMSSenderJob with $numbers, $message and $vendor
     *
     * @param  mixed $numbers
     * @param  mixed $message
     * @param  mixed $vendor
     * @return void
     */
    public function __construct(array $numbers,$message,$vendor)
    {
        $this->numbers = $numbers;
        $this->message = $message;
        $this->vendor = $vendor;
    }

    /**
     * Execute the SMS sending job.
     */
    public function handle(): void
    {

        foreach ($this->numbers as  $number) 
        {
            $this->vendor->triggerMessage(to:$number,message:$this->message);
        }
    }

    public function fail($exception = null)
    {
        // TODO: Implement fail() method.
    }
}
