<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;

class PdfController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $filename = (string) $scene->getTitle();
        if (null !== $scene->getChapter()) {
            $filename = sprintf('%s_%s', (string) $scene->getChapter()->getTitle(), $filename);
        }
        if (null !== $scene->getBook()) {
            $filename = sprintf('%s_%s', (string) $scene->getBook()->getTitle(), $filename);
        }

        return $this->responseFactory->toPdf($filename, 'scene/display.html.twig', ['scene' => $scene], null);
    }
}
