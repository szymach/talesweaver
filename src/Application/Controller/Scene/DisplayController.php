<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Scene;

class DisplayController
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
        return $this->responseFactory->fromTemplate(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
    }
}
