<?php
/**
 * Created by PhpStorm.
 * User: zkz
 * Date: 2016/11/23
 * Time: 13:52
 */

namespace KVZ\Laravel\SwitchableMail;

use Swift_Mailer;
use Illuminate\Contracts\Mail\Mailable as MailableContract;


class Mailer extends \Illuminate\Mail\Mailer
{
    /**
     * Send a new message using a view.
     *
     * @param  string|array  $view
     * @param  array  $data
     * @param  \Closure|string  $callback
     * @return void
     */
    public function send($view, array $data = [], $callback = null)
    {
        if ($view instanceof MailableContract) {
            return $view->send($this);
        }

        // First we need to parse the view, which could either be a string or an array
        // containing both an HTML and plain text versions of the view which should
        // be used when sending an e-mail. We will extract both of them out here.
        list($view, $plain, $raw) = $this->parseView($view);

        $data['message'] = $message = $this->createMessage();

        // Once we have retrieved the view content for the e-mail we will set the body
        // of this message using the HTML type, which will provide a simple wrapper
        // to creating view based emails that are able to receive arrays of data.
        $this->addContent($message, $view, $plain, $raw, $data);

        $this->callMessageBuilder($callback, $message);

        if (isset($this->to['address'])) {
            $message->to($this->to['address'], $this->to['name'], true);
        }

        $message = $message->getSwiftMessage();

        /*************************/
        // $to variable of $message is an array, this implementation is rough and temporary.
        $to = $message->getTo();
        $address = array_keys($to)[0];
        $this->determineTransport($address);
        /*************************/

        $this->sendSwiftMessage($message);
    }

    /**
     * @param $address
     */
    public function determineTransport($address)
    {
        $mailDriver = $this->determineMailDriver($address);
        app('swift.transport')->setDefaultDriver($mailDriver);
        $this->setSwiftMailer(new Swift_Mailer(app('swift.transport')->driver()));
    }

    /**
     * @param $address
     * @return string
     */
    public function determineMailDriver($address)
    {
        $divisionMap = config('switchable-mail.drivers_division');
        if (is_array($divisionMap) && isset($divisionMap)) {
            $mailServiceDomain = $this->getMailServiceFromAddress($address);
            foreach ($divisionMap as $key => $value) {
                if (in_array($mailServiceDomain, $value)) {
                    return $key;
                }
            }
        }
        $defaultDriver = config('switchable-mail.default_driver');
        return $defaultDriver ? $defaultDriver : config('mail.driver');
    }

    /**
     * @param $address
     * @return mixed
     */
    public function getMailServiceFromAddress($address)
    {
        return explode('@', $address)[1];
    }
}