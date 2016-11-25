<?php

namespace KVZ\Laravel\SwitchableMail;

use Swift_Message;

class MailDriver
{
    /**
     * Determine mail driver for the given swift message.
     *
     * @param  \Swift_Message  $message
     * @return string|null
     */
    public static function forMessage(Swift_Message $message)
    {
        $recipientsDomains = static::getMessageRecipientsDomains($message);

        return key(array_filter(
            config('switchable-mail', []),
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
    public static function getMessageRecipientsDomains(Swift_Message $message)
    {
        return array_values(array_unique(array_map(
            function ($address) {
                return strtolower(last(explode('@', $address)));
            },
            static::getMessageRecipients($message)
        )));
    }

    /**
     * Get recipients for the given message.
     *
     * @param  \Swift_Message  $message
     * @return array
     */
    public static function getMessageRecipients(Swift_Message $message)
    {
        return array_keys(array_merge(
            (array) $message->getTo(),
            (array) $message->getReplyTo(),
            (array) $message->getCc(),
            (array) $message->getBcc()
        ));
    }
}
