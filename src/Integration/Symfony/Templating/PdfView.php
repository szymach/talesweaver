<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating;

use Knp\Snappy\GeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Zend\Diactoros\Response;

class PdfView
{
    private const DEFAULT_OPTIONS = [
        'margin-top' => '10mm',
        'margin-bottom' => '10mm',
        'margin-left' => '10mm',
        'margin-right' => '10mm'
    ];

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var GeneratorInterface
     */
    private $pdfGenerator;

    public function __construct(ResponseFactoryInterface $responseFactory, GeneratorInterface $pdfGenerator)
    {
        $this->responseFactory = $responseFactory;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function createView(
        string $template,
        array $parameters,
        string $filename,
        ?array $options
    ): ResponseInterface {
        return new Response(
            $this->pdfGenerator->getOutputFromHtml(
                $this->responseFactory->fromTemplate($template, $parameters),
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
