<?php

namespace AppBundle\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtilities;

    public function __construct(
        EngineInterface $templating,
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
