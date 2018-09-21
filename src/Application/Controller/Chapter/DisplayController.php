<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Chapter;

class DisplayController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Chapter $chapter): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
    }
}
