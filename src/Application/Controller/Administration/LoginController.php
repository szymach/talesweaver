<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Administration;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\AuthenticationContext;

final class LoginController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var AuthenticationContext
     */
    private $authenticationContext;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        AuthenticationContext $authenticationContext
    ) {
        $this->responseFactory = $responseFactory;
        $this->authenticationContext = $authenticationContext;
    }

    public function __invoke(): ResponseInterface
    {
        return $this->responseFactory->fromTemplate(
            'administration/login.html.twig',
            [
                'error' => $this->authenticationContext->lastError(),
                'lastUsername' => $this->authenticationContext->lastProvidedUsername()
            ]
        );
    }
}
