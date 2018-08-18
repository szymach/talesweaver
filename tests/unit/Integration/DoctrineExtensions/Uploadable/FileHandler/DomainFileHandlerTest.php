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
        $this->assertTrue($handler->supports($this->createMock(File::class)));
    }

    /**
     * @dataProvider provideUnsupportedClasses()
     */
    public function testNotSupportingOtherFiles(string $class): void
    {
        $handler = new DomainFileHandler([$this->createMock(FileHandlerInterface::class)]);
        $this->assertFalse($handler->supports($this->createMock($class)));
    }

    public function testUsingCorrectHandler(): void
    {
        $handlerSupporting = $this->createMock(FileHandlerInterface::class);
        $handlerSupporting->expects($this->once())
            ->method('supports')
            ->with($this->isInstanceOf(SplTempFileObject::class))
            ->willReturn(true)
        ;
        $handlerSupporting->expects($this->once())
            ->method('getName')
            ->with($this->isInstanceOf(SplTempFileObject::class))
            ->willReturn('file name')
        ;
        $handlerSupporting->expects($this->once())
            ->method('getContent')
            ->with($this->isInstanceOf(SplTempFileObject::class))
            ->willReturn('file contents')
        ;

        $handlerNotSupporting = $this->createMock(FileHandlerInterface::class);
        $handlerNotSupporting->expects($this->once())
            ->method('supports')
            ->with($this->isInstanceOf(SplTempFileObject::class))
            ->willReturn(false)
        ;
        $handlerNotSupporting->expects($this->never())->method('getName');
        $handlerNotSupporting->expects($this->never())->method('getContent');

        $domainFile = new File(new SplTempFileObject());
        $handler = new DomainFileHandler([$handlerNotSupporting, $handlerSupporting]);
        $this->assertEquals('file name', $handler->getName($domainFile));
        $this->assertEquals('file contents', $handler->getContent($domainFile));
    }

    public function testExceptionWhenFileValueHasNoCorrespondingHandler()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No file handler for object of class "SplTempFileObject"');

        $handlerNotSupporting = $this->createMock(FileHandlerInterface::class);
        $handlerNotSupporting->expects($this->once())
            ->method('supports')
            ->with($this->isInstanceOf(SplTempFileObject::class))
            ->willReturn(false)
        ;

        $handler = new DomainFileHandler([$handlerNotSupporting]);
        $handler->getContent(new File(new SplTempFileObject()));
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
