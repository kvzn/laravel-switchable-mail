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
        if ($multiDrivers = config('switchable-mail', [])) {
            $recipientsDomains = SwiftMessageHelper::getRecipientsDomains($message);

            return key(array_filter(
                $multiDrivers,
                function ($value) use ($recipientsDomains) {
                    return count(array_intersect($value, $recipientsDomains)) > 0;
                },
                ARRAY_FILTER_USE_BOTH
            ));
        }
    }
}
