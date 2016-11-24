<?php
/**
 * Created by PhpStorm.
 * User: zkz
 * Date: 2016/11/24
 * Time: 13:30
 */

namespace KVZ\Laravel\SwitchableMail;


use Illuminate\Support\Manager;
use Swift_Mailer;

class SwiftMailerManager extends Manager
{
    /**
     * The swift mailers needed in applicaiton.
     *
     * @var array
     */
    protected $swiftMailers = [];

    /**
     * Get the swift mailer by \Swift_Message.
     *
     * @param \Swift_Message $message
     * @return mixed
     */
    public function getSwiftMailerForMessage(\Swift_Message $message)
    {
        // $to variable of $message is an array, this implementation is rough and temporary.
        $to = $message->getTo();
        $address = array_keys($to)[0];

        return $this->getSwiftMailerByDriverName($this->determineMailDriver($address));
    }

    /**
     * Get the swift mailer by driver name.
     *
     * @param $mailDriverName
     * @return mixed
     */
    protected function getSwiftMailerByDriverName($mailDriverName)
    {
        if (!array_key_exists($mailDriverName, $this->swiftMailers)) {
            $this->swiftMailers[$mailDriverName] = new Swift_Mailer($this->app['swift.transport']->driver($mailDriverName));
        }
        return $this->swiftMailers[$mailDriverName];
    }

    /**
     * Determine mail driver from the service domain of mail-to-address.
     *
     * @param $address
     * @return string
     */
    protected function determineMailDriver($address)
    {
        $divisionMap = config('switchable-mail.drivers_division');
        if (is_array($divisionMap) && isset($divisionMap)) {
            $mailServiceDomain = explode('@', $address)[1];
            foreach ($divisionMap as $key => $value) {
                if (in_array($mailServiceDomain, $value)) {
                    return $key;
                }
            }
        }
        return $this->getDefaultDriver();
    }

    /**
     * Get the default swift mailer.
     *
     * @return mixed
     */
    public function getDefaultSwiftMailer()
    {
        return $this->getSwiftMailerByDriverName($this->getDefaultDriver());
    }

    /**
     * Get the default mail driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        $defaultDriver = config('switchable-mail.default_driver');
        return $defaultDriver ? $defaultDriver : config('mail.driver');
    }
}