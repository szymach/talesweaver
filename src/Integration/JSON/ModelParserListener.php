<?php

declare(strict_types=1);

namespace Talesweaver\Integration\JSON;

use Talesweaver\Integration\JSON\EventParser;
use Talesweaver\Domain\Event;
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
