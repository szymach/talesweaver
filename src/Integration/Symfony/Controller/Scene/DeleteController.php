<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Scene\Delete\Command;
use Talesweaver\Domain\Scene;

class DeleteController
{
    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(MessageBus $commandBus, ResponseFactoryInterface $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Scene $scene, int $page)
    {
        $chapterId = $scene->getChapter() ? $scene->getChapter()->getId(): null;
        $this->commandBus->handle(new Command($scene));

        if (true === $request->isXmlHttpRequest()) {
            return $this->responseFactory->toJson(['success' => true]);
        }

        return null !== $chapterId
            ? $this->responseFactory->createResponse('chapter_edit', ['id' => $chapterId])
            : $this->responseFactory->createResponse('scene_list', ['page' => $page])
        ;
    }
}
