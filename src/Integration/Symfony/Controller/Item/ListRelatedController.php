<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Item;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Symfony\Pagination\Item\RelatedPaginator;

class ListRelatedController
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
     * @var RelatedPaginator
     */
    private $pagination;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        RelatedPaginator $pagination
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->pagination = $pagination;
    }

    public function __invoke(Scene $scene, int $page): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene\items\relatedList.html.twig',
                [
                    'items' => $this->pagination->getResults($scene, $page),
                    'sceneId' => $scene->getId(),
                    'sceneTitle' => $scene->getTitle(),
                    'chapterId' => $scene->getChapter() ? $scene->getChapter()->getId(): null
                ]
            )
        ]);
    }
}
