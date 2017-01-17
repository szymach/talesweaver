<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Form\Character\CharacterType;
use AppBundle\Pagination\Aggregate\CharacterAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Piotr Szymaszek
 */
class CharacterController
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
     * @var CharacterAggregate
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
        CharacterAggregate $pagination,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->manager = $manager;
        $this->pagination = $pagination;
        $this->router = $router;
    }

    public function newAction(Request $request, Scene $scene)
    {
        $form = $this->getForm(CharacterType::class, null, [
            'action' => $this->router->generate('app_character_new', [
                'id' => $scene->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $scene->addCharacter($data);
            $this->manager->persist($data);
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial\simpleForm.html.twig',
                [
                    'form' => $form->createView(),
                    'scene' => $scene,
                    'h2Title' => 'character.header.new'
                ]
            )
        ]);
    }

    public function editAction(Request $request, Character $character)
    {
        $form = $this->getForm(CharacterType::class, $character, [
            'action' => $this->router->generate('app_character_edit', [
                'id' => $character->getId()
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush();
        }

        return new JsonResponse([
            'form' => $this->templating->render(
                'partial\simpleForm.html.twig',
                ['form' => $form->createView(), 'h2Title' => 'character.header.edit']
            )
        ]);
    }

    public function listAction(Scene $scene, $page)
    {
        return new JsonResponse(['list' => $this->renderForSceneList($scene, $page)]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function deleteAction(Character $character, $page)
    {
        $this->manager->remove($character);
        $this->manager->flush();

        return new JsonResponse(['list' => $this->renderForSceneList($scene, $page)]);
    }

    public function displayAction(Character $character)
    {
        return new JsonResponse([
            'display' => $this->templating->render(
                'scene\characters\display.html.twig',
                ['character' => $character]
            )
        ]);
    }

    public function relatedAction(Scene $scene, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\relatedList.html.twig',
                [
                    'characters' => $this->pagination->getRelated($scene, $page),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function addToSceneAction(Scene $scene, Character $character)
    {
        $scene->addCharacter($character);
        $this->manager->flush();
        return new JsonResponse(['list' => $this->renderForSceneList($scene, 1)]);
    }

    /**
     * @param Scene $scene
     * @param type $page
     * @return string
     */
    private function renderForSceneList(Scene $scene, $page) : string
    {
        return $this->templating->render(
            'scene\characters\list.html.twig',
            [
                'characters' => $this->pagination->getForScene($scene, $page),
                'scene' => $scene
            ]
        );
    }

    /**
     * @param string $class
     * @return FormInterface
     */
    private function getForm($class, $data = null, $options = [])
    {
        return $this->formFactory->create($class, $data, $options);
    }
}
