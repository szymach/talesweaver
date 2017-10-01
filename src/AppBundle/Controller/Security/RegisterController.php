<?php

namespace AppBundle\Controller\Security;

use AppBundle\Form\Security\RegisterType;
use Domain\Security\Command\CreateUser;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RegisterController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->router = $router;
    }

    public function __invoke(Request $request)
    {
        $form = $this->formFactory->create(RegisterType::class);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $data = $form->getData();var_dump($request->request->all());
            $this->commandBus->handle(new CreateUser($data['username'], $data['password']));

            return new RedirectResponse($this->router->generate('login'));
        }

        return $this->templating->renderResponse(
            'security/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
