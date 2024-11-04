<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable {
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($user) {
        $this->user = $user;
    }

    public function build() {
        $settings = Settings::latest()->first();
        return $this->view('mail.welcome', ['user' => $this->user])->subject('Hey There! Welcome to ' . $settings->title);
    }
}
