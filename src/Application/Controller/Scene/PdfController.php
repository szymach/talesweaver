<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Scene;

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

    public function __invoke(Scene $scene)
    {
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
