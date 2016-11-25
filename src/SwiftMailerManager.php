<?php

namespace KVZ\Laravel\SwitchableMail;

use Illuminate\Mail\TransportManager;
use Illuminate\Support\Manager;
use Swift_Mailer;
use Swift_Message;

class SwiftMailerManager extends Manager
{
    /**
     * The mail transport manager.
     *
     * @var \Illuminate\Mail\TransportManager
     */
    protected $transportManager;

    /**
     * Get the mail transport manager.
     *
     * @return \Illuminate\Mail\TransportManager
     */
    public function getTransportManager()
    {
        return $this->transportManager;
    }

    /**
     * Set the mail transport manager.
     *
     * @param  \Illuminate\Mail\TransportManager  $manager
     * @return $this
     */
    public function setTransportManager(TransportManager $manager)
    {
        $this->transportManager = $manager;

        return $this;
    }

    /**
     * Get a swift mailer instance.
     *
     * @param  string|null  $driver
     * @return \Swift_Mailer
     */
    public function mailer($driver = null)
    {
        return $this->driver($driver);
    }

    /**
     * Get a swift mailer instance for the given message.
     *
     * @param  \Swift_Message  $message
     * @return \Swift_Mailer
     */
    public function mailerForMessage(Swift_Message $message)
    {
        return $this->mailer($this->determineMailDriver($message));
    }

    /**
     * Determine mail driver for the given message.
     *
     * @param  \Swift_Message  $message
     * @return string|null
     */
    protected function determineMailDriver(Swift_Message $message)
    {
        $recipientsDomains = $this->getMessageRecipientsDomains($message);

        return key(array_filter(
            $this->app['config']['switchable-mail'],
            function ($value) use ($recipientsDomains) {
                return count(array_intersect($value, $recipientsDomains)) > 0;
            },
            ARRAY_FILTER_USE_BOTH
        ));
    }

    /**
     * Get domains for the recipients of message.
     *
     * @param  \Swift_Message  $message
     * @return array
     */
    protected function getMessageRecipientsDomains($message)
    {
        return array_values(array_unique(array_map(
            function ($address) {
                return strtolower(last(explode('@', $address)));
            },
            $this->getMessageRecipients($message)
        )));
    }

    /**
     * Get recipients for the given message.
     *
     * @param  \Swift_Message  $message
     * @return array
     */
    protected function getMessageRecipients($message)
    {
        return array_keys(array_merge(
            (array) $message->getTo(),
            (array) $message->getReplyTo(),
            (array) $message->getCc(),
            (array) $message->getBcc()
        ));
    }

    /**
     * Create a new swift mailer instance.
     *
     * @param  string  $driver
     * @return \Swift_Mailer
     */
    protected function createDriver($driver)
    {
        return new Swift_Mailer($this->transportManager->driver($driver));
    }

    /**
     * Get the default mail driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->transportManager->getDefaultDriver();
    }
}
