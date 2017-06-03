<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Character;
use AppBundle\Form\Character\CharacterType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class EditController
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
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function __invoke(Request $request, Character $character)
    {
        $form = $this->formFactory->create(
            CharacterType::class,
            $character,
            ['action' => $this->router->generate('app_character_edit', ['id' => $character->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'character.header.edit']
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }
}
