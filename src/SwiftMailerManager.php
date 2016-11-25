<?php

namespace KVZ\Laravel\SwitchableMail;

use Illuminate\Mail\TransportManager;
use Illuminate\Support\Manager;
use Swift_Mailer;

class SwiftMailerManager extends Manager
{
    /**
     * The mail transport manager.
     *
     * @var \Illuminate\Mail\TransportManager
     */
    protected $transportManager;

    /**
     * The default driver.
     *
     * @var string
     */
    protected $defaultDriver;

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
     * Get all of the created swift mailer instances.
     *
     * @return array
     */
    public function getMailers()
    {
        return $this->drivers;
    }

    /**
     * Get the name of mail driver for the given swift mailer instance.
     *
     * @param  \Swift_Mailer  $mailer
     * @return string|null
     */
    public function getDriverForMailer(Swift_Mailer $mailer)
    {
        return array_search($mailer, $this->drivers) ?: null;
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
        return $this->defaultDriver ?: $this->transportManager->getDefaultDriver();
    }

    /**
     * Set the default mail driver name.
     *
     * @param  string  $driver
     * @return $this
     */
    public function setDefaultDriver($driver)
    {
        $this->defaultDriver = $driver;

        return $this;
    }
}
