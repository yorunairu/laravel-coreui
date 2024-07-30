<?php
// app/Mail/RfqMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RfqMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPath;
    public $rfqp;

    /**
     * Create a new message instance.
     *
     * @param string $pdfPath
     * @return void
     */
    public function __construct($pdfPath, $rfqp)
    {
        $this->pdfPath = $pdfPath;
        $this->rfqp = $rfqp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.rfqmail')
                    ->subject('RFQ file '. $this->rfqp->tenderRfq->tender->name.' for '.$this->rfqp->principle->name)
                    ->attach(storage_path('app/public/' . $this->pdfPath));
    }
}
