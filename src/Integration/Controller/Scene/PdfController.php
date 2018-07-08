<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Controller\Scene;

use Talesweaver\Domain\Scene;
use Talesweaver\Integration\Templating\PdfView;

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

    public function __invoke(Scene $scene)
    {
        $name = $scene->getTitle();
        if (null !== $scene->getChapter()) {
            $name = sprintf('%s_%s', $scene->getChapter()->getTitle(), $name);
        }
        if (null !== $scene->getBook()) {
            $name = sprintf('%s_%s', $scene->getBook()->getTitle(), $name);
        }

        return $this->pdfView->createView('scene/display.html.twig', ['scene' => $scene], $name, null);
    }
}
