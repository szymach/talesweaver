<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Security;

use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Security\AuthenticationContext;

class LoginController
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

    public function __invoke()
    {
        return $this->responseFactory->fromTemplate(
            'security/login.html.twig',
            [
                'error' => $this->authenticationContext->lastError(),
                'lastUsername' => $this->authenticationContext->lastProvidedUsername()
            ]
        );
    }
}
