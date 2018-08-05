<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\ValueObject;

use FSi\DoctrineExtensions\Uploadable;
use Gaufrette;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use SplObjectStorage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talesweaver\Domain\ValueObject\File;

class FileTest extends TestCase
{
    /**
     * @dataProvider correctClasses()
     */
    public function testCorrectFile(string $correctClass): void
    {
        $file = new File($this->createMock($correctClass));

        $this->assertInstanceOf($correctClass, $file->getValue());
    }

    public function testIncorrectFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'A domain file can be constructed only from the following classes '
            . '"Gaufrette\File, SplFileInfo, FSi\DoctrineExtensions\Uploadable\File,'
            . ' Symfony\Component\HttpFoundation\File\UploadedFile", but got "SplObjectStorage"'
        );

        new File(new SplObjectStorage());
    }

    public function correctClasses(): array
    {
        return [
            [Gaufrette\File::class],
            [SplFileInfo::class],
            [Uploadable\File::class],
            [UploadedFile::class]
        ];
    }
}
