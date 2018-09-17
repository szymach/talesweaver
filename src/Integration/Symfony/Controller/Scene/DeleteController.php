<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Command\Scene\Delete\Command;
use Talesweaver\Domain\Scene;

class DeleteController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactoryInterface $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Scene $scene, int $page)
    {
        $chapterId = $scene->getChapter() ? $scene->getChapter()->getId(): null;
        $this->commandBus->dispatch(new Command($scene));

        if ('XMLHttpRequest' == $request->getHeader('X-Requested-With')) {
            return $this->responseFactory->toJson(['success' => true]);
        }

        return null !== $chapterId
            ? $this->responseFactory->redirectToRoute('chapter_edit', ['id' => $chapterId])
            : $this->responseFactory->redirectToRoute('scene_list', ['page' => $page])
        ;
    }
}
