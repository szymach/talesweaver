<?php

namespace AppBundle\Controller\Book;

use AppBundle\Entity\Book;
use AppBundle\Form\Book\BookType;
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

    public function createAction(Request $request, $page)
    {
        $book = new Book();
        $form = $this->formFactory->create(BookType::class, $book);
        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            $this->manager->persist($book);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_book_edit', ['id' => $book->getId()])
            );
        }

        return $this->templating->renderResponse(
            'book/form.html.twig',
            ['form' => $form->createView(), 'book' => $book, 'page' => $page]
        );
    }
}
