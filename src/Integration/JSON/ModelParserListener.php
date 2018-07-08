<?php

declare(strict_types=1);

namespace Integration\JSON;

use Integration\JSON\EventParser;
use Domain\Event;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModelParserListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postLoad(Event $entity, LifecycleEventArgs $event)
    {
        $entity->parseModel($this->container->get(EventParser::class));
    }
}
