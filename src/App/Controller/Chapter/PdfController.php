<?php

declare(strict_types=1);

namespace App\Controller\Chapter;

use App\Templating\PdfView;
use Domain\Chapter;

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

    public function __invoke(Chapter $chapter)
    {
        $name = $chapter->getTitle();
        if (null !== $chapter->getBook()) {
            $name = sprintf('%s_%s', $chapter->getBook()->getTitle(), $name);
        }

        return $this->pdfView->createView('chapter/display.html.twig', ['chapter' => $chapter], $name, null);
    }
}
