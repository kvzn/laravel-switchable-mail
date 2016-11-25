<?php

namespace KVZ\Laravel\SwitchableMail;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Mailer as BaseMailer;

class Mailer extends BaseMailer
{
    /**
     * The Swift Mailer Manager instance.
     *
     * @var \KVZ\Laravel\SwitchableMail\SwiftMailerManager
     */
    protected $swiftManager;

    /**
     * Get the Swift Mailer Manager instance.
     *
     * @return \KVZ\Laravel\SwitchableMail\SwiftMailerManager
     */
    public function getSwiftMailerManager()
    {
        return $this->swiftManager;
    }

    /**
     * Set the Swift Mailer Manager instance.
     *
     * @param  \KVZ\Laravel\SwitchableMail\SwiftMailerManager  $manager
     * @return void
     */
    public function setSwiftMailerManager($manager)
    {
        $this->swiftManager = $manager;
    }

    /**
     * Send a Swift Message instance.
     *
     * @param  \Swift_Message  $message
     * @return void
     */
    protected function sendSwiftMessage($message)
    {
        if ($this->events) {
            $this->events->fire(new MessageSending($message));
        }

        $swift = $this->swiftManager->mailer(MailDriver::forMessage($message));

        try {
            return $swift->send($message, $this->failedRecipients);
        } finally {
            $this->forceReconnection($swift);
        }
    }

    /**
     * Force the transport to re-connect.
     *
     * This will prevent errors in daemon queue situations.
     *
     * @param  \Swift_Mailer
     * @return void
     */
    protected function forceReconnection($swiftMailer = null)
    {
        if (is_null($swiftMailer)) {
            $swiftMailer = $this->getSwiftMailer();
        }

        $swiftMailer->getTransport()->stop();
    }

    /**
     * Get the Swift Mailer instance.
     *
     * @return \Swift_Mailer
     */
    public function getSwiftMailer()
    {
        return $this->swiftManager->mailer();
    }
}
