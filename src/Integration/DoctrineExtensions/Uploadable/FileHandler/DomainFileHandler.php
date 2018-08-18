<?php

declare(strict_types=1);

namespace Talesweaver\Integration\DoctrineExtensions\Uploadable\FileHandler;

use Assert\Assertion;
use FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface as Handler;
use InvalidArgumentException;
use Talesweaver\Domain\ValueObject\File;

class DomainFileHandler implements Handler
{
    /**
     * @var Handler[]
     */
    private $handlers;

    /**
     * @var Handler[]
     */
    private $handlersByFile = [];

    public function __construct(array $handlers)
    {
        Assertion::notEmpty($handlers);
        Assertion::allIsInstanceOf($handlers, Handler::class);

        $this->handlers = $handlers;
    }

    /**
     * @param File $file
     * @return string
     */
    public function getContent($file): string
    {
        return $this->getHandlerForFile($file->getValue())->getContent($file->getValue());
    }

    /**
     * @param File $file
     * @return string
     */
    public function getName($file): string
    {
        return $this->getHandlerForFile($file->getValue())->getName($file->getValue());
    }

    public function supports($file): bool
    {
        return true === $file instanceof File;
    }

    private function getHandlerForFile(object $file): Handler
    {
        $fileClass = get_class($file);
        if (true === \array_key_exists($fileClass, $this->handlersByFile)) {
            return $this->handlersByFile[$fileClass];
        }

        return $this->findInHandlers($file);
    }

    private function findInHandlers(object $file): Handler
    {
        $handler = \array_reduce(
            $this->handlers,
            function (?Handler $accumulator, Handler $handler) use ($file): ?Handler {
                return true === $handler->supports($file) ? $handler : $accumulator;
            },
            null
        );

        if (null === $handler) {
            throw new InvalidArgumentException(
                \sprintf('No file handler for object of class "%s"', get_class($file))
            );
        }

        $this->handlersByFile[get_class($file)] = $handler;

        return $handler;
    }
}
