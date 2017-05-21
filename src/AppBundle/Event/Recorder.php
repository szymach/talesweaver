<?php

namespace AppBundle\Event;

use SimpleBus\Message\Recorder\RecordsMessages;

class Recorder implements RecordsMessages
{
    /**
     * @var Event[]
     */
    private $messages = [];

    /**
     * @param Event $message
     */
    public function record($message)
    {
        if (in_array($message, $this->messages, true)) {
            return;
        }

        $this->messages[get_class($message)][] = $message;
    }

    public function recordedMessagesOfClass($class)
    {
        return isset($this->messages[$class]) ? $this->messages[$class] : [];
    }

    /**
     * @return Event[]
     */
    public function recordedMessages()
    {
        return $this->messages;
    }

    public function eraseMessages()
    {
        unset($this->messages);
        $this->messages = [];
    }
}
