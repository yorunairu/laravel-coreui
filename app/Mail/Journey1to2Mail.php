<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Journey1to2Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $sales;

    /**
     * Create a new message instance.
     */
    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Important: Sales Data Transferred to Procurement')
                    ->view('emails.journey1to2mail')
                    ->with('sales', $this->sales);
    }
}
