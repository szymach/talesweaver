<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Templating\PdfView;

class PdfController
{
    /**
     * @var PdfView
     */
    private $pdfView;

    public function __construct(PdfView $pdfView)
    {
        $this->pdfView = $pdfView;
    }

    public function __invoke(Chapter $chapter): ResponseInterface
    {
        $name = $chapter->getTitle();
        if (null !== $chapter->getBook()) {
            $name = sprintf('%s_%s', $chapter->getBook()->getTitle(), (string) $name);
        }

        return $this->pdfView->createView(
            'chapter/display.html.twig',
            ['chapter' => $chapter],
            (string) $name,
            null
        );
    }
}
