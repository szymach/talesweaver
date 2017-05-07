<?php

namespace AppBundle\Controller\Book;

use AppBundle\Entity\Book;
use AppBundle\Form\Book\BookType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
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
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function editAction(Request $request, Book $book, $page)
    {
        $form = $this->formFactory->create(BookType::class, $book);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'book/form.html.twig',
            ['form' => $form->createView(), 'page' => $page]
        );
    }
}
