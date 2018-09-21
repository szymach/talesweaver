<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Scene;

use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Domain\Chapter;

class RelatedListController
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
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        HtmlContent $htmlContent,
        QueryBus $queryBus
    ) {
        $this->responseFactory = $responseFactory;
        $this->htmlContent = $htmlContent;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Chapter $chapter, int $page)
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'scene/related/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'chapterTitle' => $chapter->getTitle(),
                    'scenes' => $this->queryBus->query(new ScenesPage($chapter, $page)),
                    'page' => $page
                ]
            )
        ]);
    }
}
