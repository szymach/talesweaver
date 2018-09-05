<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Templating\Scene;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Talesweaver\Domain\Scene;

class DisplayView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $templating;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function createView(Scene $scene): Response
    {
        return $this->templating->renderResponse(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
    }
}
