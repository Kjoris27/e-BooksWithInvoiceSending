<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use PDF;

class TestMailWithPdf extends Command
{
    protected $signature = 'test:mailwithpdf';

    protected $description = 'Send a test email with a PDF attachment';

    public function handle()
    {
        $data["email"] = "example@gmail.com";
        $data["title"] = "Email testing title";
        $data["body"] = "Email testing body";

        $pdf = PDF::loadView('mail', $data);

        Mail::send('mail', $data, function ($message) use ($data, $pdf) {
            $message->to($data["email"]);
            $message->subject($data["title"]);
            $message->attachData($pdf->output(), "test.pdf");
        });

        $this->info("Email has been sent.");
    }
}
