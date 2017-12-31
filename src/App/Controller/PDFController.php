<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Scene;
use Knp\Snappy\GeneratorInterface;
use App\Templating\Engine;
use Symfony\Component\HttpFoundation\Response;

class PDFController
{
    /**
     * @var GeneratorInterface
     */
    private $pdfGenerator;

    /**
     * @var Engine
     */
    private $templating;

    public function __construct(Engine $templating, GeneratorInterface $pdfGenerator)
    {
        $this->templating = $templating;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function sceneAction(Scene $scene)
    {
        $html = $this->templating->render(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
        $name = $scene->getTitle();
        if ($scene->getChapter()) {
            $name = sprintf('%s_%s', $scene->getChapter()->getTitle(), $name);
        }
        if ($scene->getBook()) {
            $name = sprintf('%s_%s', $scene->getBook()->getTitle(), $name);
        }

        return $this->renderPDFResponse($html, $this->createFileName($name));
    }

    public function chapterAction(Chapter $chapter)
    {
        $html = $this->templating->render(
            'chapter/display.html.twig',
            ['chapter' => $chapter]
        );
        $name = $chapter->getTitle();
        if ($chapter->getBook()) {
            $name = sprintf('%s_%s', $chapter->getBook()->getTitle(), $name);
        }

        return $this->renderPDFResponse($html, $this->createFileName($name));
    }

    /**
     * @param string $html
     * @return Response
     */
    private function renderPDFResponse(string $html, $filename)
    {
        return new Response(
            $this->pdfGenerator->getOutputFromHtml($html, $this->getOptions()),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename)
            ]
        );
    }

    private function getOptions()
    {
        return [
            'margin-top' => '10mm',
            'margin-bottom' => '10mm',
            'margin-left' => '10mm',
            'margin-right' => '10mm',
        ];
    }

    private function createFileName($name)
    {
        return sprintf('%s.pdf', mb_strtolower($name));
    }
}
