<?php

namespace App\Traits;

trait MailConfigTrait {
    protected function setMailConfig($mailConfig) {
        config()->set('mail.driver', $mailConfig->driver);
        config()->set('mail.host', $mailConfig->host);
        config()->set('mail.port', $mailConfig->port);
        config()->set('mail.from.address', $mailConfig->from_address);
        config()->set('mail.from.name', $mailConfig->from_name);
        config()->set('mail.username', $mailConfig->username);
        config()->set('mail.password', $mailConfig->password);
        config()->set('mail.encryption', $mailConfig->encryption);
    }
}
