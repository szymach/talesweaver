<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Location;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Pagination\Location\LocationPaginator;

class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var LocationPaginator
     */
    private $pagination;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        LocationPaginator $pagination
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->pagination = $pagination;
    }

    public function __invoke(Scene $scene, int $page): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene\locations\list.html.twig',
                [
                    'locations' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null,
                    'page' => $page
                ]
            )
        ]);
    }
}
