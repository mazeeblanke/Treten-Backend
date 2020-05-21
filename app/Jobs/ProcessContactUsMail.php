<?php

namespace App\Jobs;

use App\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessContactUsMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contactUs;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContactUs $contactUs)
    {
        $this->contactUs = $contactUs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
