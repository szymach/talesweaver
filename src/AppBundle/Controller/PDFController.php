<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Chapter;
use AppBundle\Entity\Scene;
use Knp\Snappy\GeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class PDFController
{
    /**
     * @var GeneratorInterface $pdfGenerator
     */
    private $pdfGenerator;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating, GeneratorInterface $pdfGenerator)
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
