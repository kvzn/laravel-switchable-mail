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
        // $to variable of $message is an array, this implementation is rough and temporary.
        $to = $message->getTo();
        $address = array_keys($to)[0];

        $driver = $this->determineMailDriver($address);

        return $this->mailer($driver);
    }

    /**
     * Determine mail driver from the service domain of mail-to-address.
     *
     * @param  string  $address
     * @return string|null
     */
    protected function determineMailDriver($address)
    {
        $divisionMap = $this->app['config']['switchable-mail'];

        if (is_array($divisionMap) && isset($divisionMap)) {
            $mailServiceDomain = explode('@', $address)[1];
            foreach ($divisionMap as $key => $value) {
                if (in_array($mailServiceDomain, $value)) {
                    return $key;
                }
            }
        }
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
