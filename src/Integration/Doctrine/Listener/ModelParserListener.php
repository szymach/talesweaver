<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Talesweaver\Domain\Event;
use Talesweaver\Integration\Symfony\JSON\EventParser;

class ModelParserListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventParser|null
     */
    private $parser;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postLoad(Event $entity, LifecycleEventArgs $event)
    {
        $entity->setParsedModel($this->getParser()->parse($entity->getModel()));
    }

    private function getParser(): EventParser
    {
        if (null === $this->parser) {
            $parser = $this->container->get(EventParser::class);
            if (false === $parser instanceof EventParser) {
                throw new RuntimeException(sprintf(
                    'Returned instance of model parser is not of class "%s"!',
                    get_class($parser)
                ));
            }

            $this->parser = $parser;
        }

        return $this->parser;
    }
}
