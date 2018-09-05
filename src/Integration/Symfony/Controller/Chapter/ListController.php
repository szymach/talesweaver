<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ChapterPaginator;

class ListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ChapterPaginator
     */
    private $pagination;

    public function __construct(ResponseFactoryInterface $responseFactory, ChapterPaginator $pagination)
    {
        $this->responseFactory = $responseFactory;
        $this->pagination = $pagination;
    }

    public function __invoke(int $page): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getResults($page), 'page' => $page]
        );
    }
}
