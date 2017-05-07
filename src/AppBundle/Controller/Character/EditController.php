<?php

namespace AppBundle\Controller\Character;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Form\Character\CharacterType;
use AppBundle\Pagination\CharacterPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @var CharacterPaginator
     */
    private $pagination;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        ObjectManager $manager,
        CharacterPaginator $pagination,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
        $this->router = $router;
    }

    public function editAction(Request $request, Character $character)
    {
        $form = $this->formFactory->create(
            CharacterType::class,
            $character,
            ['action' => $this->router->generate('app_character_edit', ['id' => $character->getId()])]
        );

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'title' => 'character.header.edit']
            )
        ], !$form->isSubmitted() || $form->isValid() ? 200 : 400);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function addToSceneAction(Scene $scene, Character $character)
    {
        $scene->addCharacter($character);
        $this->manager->flush();
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->pagination->getForScene($scene, 1),
                    'scene' => $scene
                ]
            )
        ]);
    }
}
