<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Character;
use AppBundle\Entity\Repository\CharacterRepository;
use AppBundle\Entity\Scene;
use AppBundle\Form\Character\CharacterType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

    public function newAction(Request $request, Scene $scene)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

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
                'scene\characters\form.html.twig',
                ['form' => $form->createView(), 'scene' => $scene]
            )
        ]);
    }

    public function editAction(Request $request, Character $character)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

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
                'scene\characters\form.html.twig',
                ['form' => $form->createView()]
            )
        ]);
    }

    public function listAction(Request $request, Scene $scene)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->getRepository()->getForScene($scene),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @ParamConverter("scene", options={"id" = "scene_id"})
     * @ParamConverter("character", options={"id" = "character_id"})
     */
    public function deleteAction(Request $request, Scene $scene, Character $character)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $this->manager->remove($character);
        $this->manager->flush();

        return new JsonResponse([
            'list' => $this->templating->render(
                'scene\characters\list.html.twig',
                [
                    'characters' => $this->getRepository()->getForScene($scene),
                    'scene' => $scene
                ]
            )
        ]);
    }

    /**
     * @param string $class
     * @return FormInterface
     */
    private function getForm($class, $data = null, $options = [])
    {
        return $this->formFactory->create($class, $data, $options);
    }

    /**
     * @return CharacterRepository
     */
    private function getRepository()
    {
        return $this->manager->getRepository(Character::class);
    }
}
