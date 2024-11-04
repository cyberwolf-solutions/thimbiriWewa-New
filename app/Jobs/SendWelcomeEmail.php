<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Models\MailConfig;
use App\Traits\MailConfigTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MailConfigTrait;
    public $email;

    /**
     * Create a new job instance.
     */
    public function __construct($email) {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {

        $user = $this->email;
        $mailConfig = MailConfig::latest()->first();
        if ($mailConfig) {
            $this->setMailConfig($mailConfig);
            Mail::to($user['email'])->send(new WelcomeEmail($user));
        }
    }
}
