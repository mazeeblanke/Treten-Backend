<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BecomeAnInstructorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $builder = $this
            ->from($this->data['email'])
            ->markdown('emails.becomeaninstructor')
            ->with($this->data);
        if (isset($this->data['file']))
        {
            $builder = $builder->attach($this->data['file']);
        }    

        return $builder;
    }
}
