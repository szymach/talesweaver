<?php

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Form\Chapter\ChapterType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class CreateController
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
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function __invoke(Request $request)
    {
        $chapter = new Chapter();
        $form = $this->formFactory->create(ChapterType::class, $chapter);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->persist($chapter);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_chapter_edit', ['id' => $chapter->getId()])
            );
        }

        return $this->templating->renderResponse(
            'chapter/form.html.twig',
            ['form' => $form->createView()]
        );
    }
}
