<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Integration\DoctrineExtensions\Uploadable\FileHandler;

use Codeception\Test\Unit;
use FSi\DoctrineExtensions\Uploadable;
use FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface;
use Gaufrette;
use InvalidArgumentException;
use SplFileInfo;
use SplTempFileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Integration\DoctrineExtensions\Uploadable\FileHandler\DomainFileHandler;

class DomainFileHandlerTest extends Unit
{
    public function testSupportingDomainFile(): void
    {
        $handler = new DomainFileHandler([$this->createMock(FileHandlerInterface::class)]);
        self::assertTrue($handler->supports($this->createMock(File::class)));
    }

    /**
     * @dataProvider provideUnsupportedClasses()
     */
    public function testNotSupportingOtherFiles(string $class): void
    {
        $handler = new DomainFileHandler([$this->createMock(FileHandlerInterface::class)]);
        self::assertFalse($handler->supports($this->createMock($class)));
    }

    public function testUsingCorrectHandler(): void
    {
        $handlerSupporting = $this->createMock(FileHandlerInterface::class);
        $handlerSupporting->expects(self::once())
            ->method('supports')
            ->with(self::isInstanceOf(SplTempFileObject::class))
            ->willReturn(true)
        ;
        $handlerSupporting->expects(self::once())
            ->method('getName')
            ->with(self::isInstanceOf(SplTempFileObject::class))
            ->willReturn('file name')
        ;
        $handlerSupporting->expects(self::once())
            ->method('getContent')
            ->with(self::isInstanceOf(SplTempFileObject::class))
            ->willReturn('file contents')
        ;

        $handlerNotSupporting = $this->createMock(FileHandlerInterface::class);
        $handlerNotSupporting->expects(self::once())
            ->method('supports')
            ->with(self::isInstanceOf(SplTempFileObject::class))
            ->willReturn(false)
        ;
        $handlerNotSupporting->expects(self::never())->method('getName');
        $handlerNotSupporting->expects(self::never())->method('getContent');

        $domainFile = File::fromNullableValue(new SplTempFileObject());
        $handler = new DomainFileHandler([$handlerNotSupporting, $handlerSupporting]);
        self::assertEquals('file name', $handler->getName($domainFile));
        self::assertEquals('file contents', $handler->getContent($domainFile));
    }

    public function testExceptionWhenFileValueHasNoCorrespondingHandler()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('No file handler for object of class "SplTempFileObject"');

        $handlerNotSupporting = $this->createMock(FileHandlerInterface::class);
        $handlerNotSupporting->expects(self::once())
            ->method('supports')
            ->with(self::isInstanceOf(SplTempFileObject::class))
            ->willReturn(false)
        ;

        $handler = new DomainFileHandler([$handlerNotSupporting]);
        $handler->getContent(File::fromNullableValue(new SplTempFileObject()));
    }

    public function provideUnsupportedClasses(): array
    {
        return [
            [Gaufrette\File::class],
            [SplFileInfo::class],
            [Uploadable\File::class],
            [UploadedFile::class]
        ];
    }
}
