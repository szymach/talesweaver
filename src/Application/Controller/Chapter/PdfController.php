<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Chapter;

class PdfController
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
        $filename = (string) $chapter->getTitle();
        if (null !== $chapter->getBook()) {
            $filename = sprintf('%s_%s', $chapter->getBook()->getTitle(), $filename);
        }

        return $this->responseFactory->toPdf(
            $filename,
            'chapter/display.html.twig',
            ['chapter' => $chapter],
            null
        );
    }
}
