<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Talesweaver\Domain\Event;
use Talesweaver\Integration\Symfony\JSON\EventParser;

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
        /* @var $parser EventParser */
        $parser = $this->container->get(EventParser::class);
        $entity->setParsedModel($parser->parse($entity->getModel()));
    }
}
