<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Templating;

use Knp\Snappy\GeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class PdfView
{
    private const DEFAULT_OPTIONS = [
        'margin-top' => '10mm',
        'margin-bottom' => '10mm',
        'margin-left' => '10mm',
        'margin-right' => '10mm'
    ];

    /**
     * @var GeneratorInterface
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

    public function createView(string $template, array $parameters, string $filename, ?array $options): Response
    {
        return new Response(
            $this->pdfGenerator->getOutputFromHtml(
                $this->templating->render($template, $parameters),
                $options ?? self::DEFAULT_OPTIONS
            ),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $filename)
            ]
        );
    }
}
