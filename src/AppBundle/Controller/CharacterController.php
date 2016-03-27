<?php

namespace AppBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

use AppBundle\Entity\Character;
use AppBundle\Entity\Scene;
use AppBundle\Form\Character\CharacterType;

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

    public function addAction(Request $request, Scene $scene)
    {
        $form = $this->getForm(CharacterType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $scene->addCharacter($form->getData());
            $this->manager->flush();
            
            return new RedirectResponse(
                $this->router->generate('app_scene_edit', ['id' => $scene->getId()])
            );
        }

        return $this->templating->renderResponse(
            'character\form.html.twig',
            ['form' => $form->createView()]
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
