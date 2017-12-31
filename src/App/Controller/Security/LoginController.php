<?php

namespace App\Controller\Security;

use App\Templating\Engine;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController
{
    /**
     * @var Engine
     */
    private $templating;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtilities;

    public function __construct(
        Engine $templating,
        AuthenticationUtils $authenticationUtilities
    ) {
        $this->templating = $templating;
        $this->authenticationUtilities = $authenticationUtilities;
    }

    public function __invoke()
    {
        return $this->templating->renderResponse(
            'security/login.html.twig',
            ['error' => $this->authenticationUtilities->getLastAuthenticationError()]
        );
    }
}
