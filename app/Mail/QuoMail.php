<?php
// app/Mail/RfqMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPath;
    public $sales;

    /**
     * Create a new message instance.
     *
     * @param string $pdfPath
     * @return void
     */
    public function __construct($pdfPath, $sales)
    {
        $this->pdfPath = $pdfPath;
        $this->sales = $sales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.quomail')
                    ->subject('Quotation file '. $this->sales->name)
                    ->attach(storage_path('app/public/' . $this->pdfPath));
    }
}
