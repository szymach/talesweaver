<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use function is_xml_http_request;

final class DisplayController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    public function __construct(
        SceneResolver $sceneResolver,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $parameters = ['title' => $scene->getTitle(), 'text' => $scene->getText()];
        if (true === is_xml_http_request($request)) {
            $response = $this->apiResponseFactory->display('display/modal.html.twig', $parameters);
        } else {
            $response = $this->responseFactory->fromTemplate('display/standalone.html.twig', $parameters);
        }

        return $response;
    }
}
