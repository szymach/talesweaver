<?php

namespace AppBundle\Controller\Book;

use AppBundle\Book\EditBook;
use AppBundle\Entity\Book;
use AppBundle\Form\Book\EditType;
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
        $editBook = new EditBook($book);
        $form = $this->formFactory->create(EditType::class, $editBook);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $editBook->edit();
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'book/editForm.html.twig',
            ['form' => $form->createView(), 'book' => $book, 'page' => $page]
        );
    }
}
