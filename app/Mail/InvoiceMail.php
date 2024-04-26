<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;



// InvoiceMail.php



class InvoiceMail extends Mailable
{
    public $pdfPath;

    public function __construct($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Invoice')
            ->attach($this->pdfPath, [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ])
            ->view('invoice');
    }
}
