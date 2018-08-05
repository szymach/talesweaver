<?php

declare(strict_types=1);

namespace Talesweaver\Domain\ValueObject;

use FSi\DoctrineExtensions\Uploadable;
use Gaufrette;
use InvalidArgumentException;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
    public const ALLOWED_CLASSES = [
        Gaufrette\File::class,
        SplFileInfo::class,
        Uploadable\File::class,
        UploadedFile::class
    ];

    /**
     * @var Gaufrette\File|Uploadable\File|SplFileInfo|UploadedFile
     */
    private $value;

    public function __construct(?object $value)
    {
        if (null !== $value) {
            $this->validate($value);
        }

        $this->value = $value;
    }

    /**
     * @return Gaufrette\File|Uploadable\File|SplFileInfo|UploadedFile
     */
    public function getValue()
    {
        return $this->value;
    }

    private function validate(?object $value): void
    {
        $correctInstances = array_filter(
            self::ALLOWED_CLASSES,
            function (string $allowedClass) use ($value): bool {
                return true === $value instanceof $allowedClass;
            }
        );
        if (0 === count($correctInstances)) {
            throw new InvalidArgumentException(sprintf(
                'A domain file can be constructed only from the following classes "%s", but got "%s"',
                implode(', ', self::ALLOWED_CLASSES),
                get_class($value)
            ));
        }
    }
}
