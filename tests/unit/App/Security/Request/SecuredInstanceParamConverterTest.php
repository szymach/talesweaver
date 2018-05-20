<?php

declare(strict_types=1);

namespace App\Tests\Security\Request;

use App\Repository\Interfaces\FindableByIdRepository;
use App\Security\Request\SecuredInstanceParamConverter;
use Domain\Entity\Book;
use Domain\Entity\Chapter;
use Domain\Entity\Scene;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecuredInstanceParamConverterTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(ParamConverterInterface::class, new SecuredInstanceParamConverter([]));
    }

    public function testSupports()
    {
        $repository = $this->createMock(FindableByIdRepository::class);
        $repository->expects($this->once())->method('getClassName')->willReturn(Book::class);

        $converter = new SecuredInstanceParamConverter([$repository]);

        $configuration = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $configuration->expects($this->once())->method('getClass')->willReturn(Book::class);

        $this->assertTrue($converter->supports($configuration));
    }

    public function testNotSupporting()
    {
        $repository = $this->createMock(FindableByIdRepository::class);
        $repository->expects($this->once())->method('getClassName')->willReturn(Chapter::class);

        $converter = new SecuredInstanceParamConverter([$repository]);

        $configuration = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $configuration->expects($this->once())->method('getClass')->willReturn(Book::class);

        $this->assertFalse($converter->supports($configuration));
    }

    public function testSuccessfulApplication()
    {
        $id = (string) Uuid::uuid4();
        $chapter = $this->getMockBuilder(Chapter::class)->disableOriginalConstructor()->getMock();
        $repository = $this->createMock(FindableByIdRepository::class);
        $repository->expects($this->once())->method('getClassName')->willReturn(Chapter::class);
        $repository->expects($this->once())->method('find')->with($id)->willReturn($chapter);

        $attributes = $this->createMock(ParameterBag::class);
        $attributes->expects($this->once())->method('get')->willReturn($id);
        $attributes->expects($this->once())->method('set')->with('book', $chapter);
        $request = $this->createMock(Request::class);
        $request->attributes = $attributes;

        $configuration = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $configuration->expects($this->once())->method('getOptions')->willReturn([]);
        $configuration->expects($this->once())->method('getClass')->willReturn(Chapter::class);
        $configuration->expects($this->once())->method('getName')->willReturn('book');

        $converter = new SecuredInstanceParamConverter([$repository]);
        $this->assertTrue($converter->apply($request, $configuration));
    }

    public function testSuccessfulApplicationWithCustomId()
    {
        $id = (string) Uuid::uuid4();
        $chapter = $this->getMockBuilder(Chapter::class)->disableOriginalConstructor()->getMock();
        $repository = $this->createMock(FindableByIdRepository::class);
        $repository->expects($this->once())->method('getClassName')->willReturn(Chapter::class);
        $repository->expects($this->once())->method('find')->with($id)->willReturn($chapter);

        $attributes = $this->createMock(ParameterBag::class);
        $attributes->expects($this->once())->method('get')->with('custom_id')->willReturn($id);
        $attributes->expects($this->once())->method('set')->with('book', $chapter);
        $request = $this->createMock(Request::class);
        $request->attributes = $attributes;

        $configuration = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $configuration->expects($this->once())->method('getOptions')->willReturn(['id' => 'custom_id']);
        $configuration->expects($this->once())->method('getClass')->willReturn(Chapter::class);
        $configuration->expects($this->once())->method('getName')->willReturn('book');

        $converter = new SecuredInstanceParamConverter([$repository]);
        $this->assertTrue($converter->apply($request, $configuration));
    }

    public function testExceptionWhenNoId()
    {
        $attributes = $this->createMock(ParameterBag::class);
        $attributes->expects($this->once())->method('get')->willReturn(null);
        $attributes->expects($this->never())->method('set');
        $request = $this->createMock(Request::class);
        $request->attributes = $attributes;
        $request->expects($this->once())->method('getRequestUri')->willReturn('/');

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('No "id" paramater found in path "/"');

        $converter = new SecuredInstanceParamConverter([]);
        $converter->apply(
            $request,
            $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testExceptionWhenInvalidId()
    {
        $attributes = $this->createMock(ParameterBag::class);
        $attributes->expects($this->once())->method('get')->willReturn(1);
        $attributes->expects($this->never())->method('set');
        $request = $this->createMock(Request::class);
        $request->attributes = $attributes;
        $configuration = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $configuration->expects($this->once())->method('getClass')->willReturn(Scene::class);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Invalid UUID "1" for class "Domain\Entity\Scene"!');

        $converter = new SecuredInstanceParamConverter([]);
        $converter->apply($request, $configuration);
    }

    public function testExceptionWhenObjectNotFound()
    {
        $id = (string) Uuid::uuid4();
        $repository = $this->createMock(FindableByIdRepository::class);
        $repository->expects($this->once())->method('getClassName')->willReturn(Scene::class);
        $repository->expects($this->once())->method('find')->with($id)->willReturn(null);

        $attributes = $this->createMock(ParameterBag::class);
        $attributes->expects($this->once())->method('get')->willReturn($id);
        $attributes->expects($this->never())->method('set');
        $request = $this->createMock(Request::class);
        $request->attributes = $attributes;

        $configuration = $this->getMockBuilder(ParamConverter::class)->disableOriginalConstructor()->getMock();
        $configuration->expects($this->exactly(2))->method('getClass')->willReturn(Scene::class);
        $configuration->expects($this->once())->method('getOptions')->willReturn([]);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(
            sprintf('Could not find object of class "Domain\Entity\Scene" for id "%s"', $id)
        );

        $converter = new SecuredInstanceParamConverter([$repository]);
        $converter->apply($request, $configuration);
    }
}
