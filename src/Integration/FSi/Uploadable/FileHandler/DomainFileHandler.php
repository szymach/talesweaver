<?php

declare(strict_types=1);

namespace Talesweaver\Integration\FSi\Uploadable\FileHandler;

use FSi\Bundle\DoctrineExtensionsBundle\Listener\Uploadable\FileHandler\SymfonyUploadedFileHandler;
use FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface;
use FSi\DoctrineExtensions\Uploadable\FileHandler\GaufretteHandler;
use FSi\DoctrineExtensions\Uploadable\FileHandler\SplFileInfoHandler;
use Gaufrette;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talesweaver\Domain\ValueObject;

class DomainFileHandler implements FileHandlerInterface
{
    /**
     * @var GaufretteHandler
     */
    private $gaufretteHandler;

    /**
     * @var SplFileInfoHandler
     */
    private $splFileHandler;

    /**
     * @var SymfonyUploadedFileHandler
     */
    private $symfonyFileHandler;

    public function __construct(
        GaufretteHandler $gaufretteHandler,
        SplFileInfoHandler $splFileHandler,
        SymfonyUploadedFileHandler $symfonyFileHandler
    ) {
        $this->gaufretteHandler = $gaufretteHandler;
        $this->splFileHandler = $splFileHandler;
        $this->symfonyFileHandler = $symfonyFileHandler;
    }

    /**
     * @param ValueObject\File $file
     * @return string
     */
    public function getContent($file): string
    {
        $wrappedFile = $file->getValue();
        return $this->getHandlerForFile($wrappedFile)->getContent($wrappedFile);
    }

    /**
     * @param ValueObject\File $file
     * @return string
     */
    public function getName($file): string
    {
        $wrappedFile = $file->getValue();
        return $this->getHandlerForFile($wrappedFile)->getName($wrappedFile);
    }

    public function supports($file): bool
    {
        return true === $file instanceof ValueObject\File;
    }

    private function getHandlerForFile($file): FileHandlerInterface
    {
        switch (true) {
            case $file instanceof Gaufrette\File:
                $handler = $this->gaufretteHandler;
                break;
            case $file instanceof SplFileInfo:
                $handler = $this->splFileHandler;
                break;
            case $file instanceof UploadedFile:
                $handler = $this->symfonyFileHandler;
                break;
            default:
                throw new RuntimeException(sprintf(
                    'No handler for "%s"',
                    true === is_object($file) ? get_class($file) : gettype($file)
                ));
        }

        return $handler;
    }
}
