<?php

namespace AppBundle\Entity\Translation;

use AppBundle\Entity\Traits\LocaleTrait;

class EventTranslation
{
    use LocaleTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Event
     */
    private $event;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(Event $event = null)
    {
        $this->event = $event;
    }
}
