<?php
/**
 * Created by PhpStorm.
 * User: zkz
 * Date: 2016/11/23
 * Time: 13:52
 */

namespace KVZ\Laravel\SwitchableMail;

use Illuminate\Mail\Events\MessageSending;

class Mailer extends \Illuminate\Mail\Mailer
{
    protected function sendSwiftMessage($message)
    {
        if ($this->events) {
            $this->events->fire(new MessageSending($message));
        }

        $swiftMailerManager = app('swift.mailerManager');

        try {
            $swiftMailer = $swiftMailerManager->getSwiftMailerForMessage($message);
            return $swiftMailer->send($message, $this->failedRecipients);
        } finally {
            $this->forceReconnection();
        }
    }
}