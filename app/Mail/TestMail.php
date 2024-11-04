<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable {
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     */
    public function __construct() {
        //
    }
    public function build() {

        return $this->view('mail.test')->subject('Test Mail to check the Mail Configurations');
    }
}
