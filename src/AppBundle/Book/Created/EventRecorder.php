<?php

namespace AppBundle\Book\Created;

use InvalidArgumentException;
use SimpleBus\Message\Recorder\RecordsMessages;

class EventRecorder implements RecordsMessages
{
    /**
     * @var Event[]
     */
    private $messages = [];

    /**
     * @param Event $message
     * @throws InvalidArgumentException
     */
    public function record($message)
    {
        if (!($message instanceof Event)) {
            throw new InvalidArgumentException(sprintf(
                'Expected message of class "%s", got "%s"',
                self::class,
                is_object($message) ? get_class($message) : gettype($message)
            ));
        }

        if (in_array($message, $this->messages, true)) {
            return;
        }

        $this->messages[] = $message;
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
