<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\Book\BookType;
use AppBundle\Pagination\Aggregate\BookAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class BookController
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
     * @var BookAggregate
     */
    private $pagination;

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
        BookAggregate $pagination,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->pagination = $pagination;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function newAction(Request $request, $page)
    {
        $book = new Book();
        $form = $this->getForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $this->manager->persist($data);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_book_edit', ['id' => $data->getId()])
            );
        }

        return $this->templating->renderResponse(
            'book/form.html.twig',
            ['form' => $form->createView(), 'book' => $book, 'page' => $page]
        );
    }

    public function editAction(Request $request, Book $book, $page)
    {
        $form = $this->getForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'book/form.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
                'chapters' => $this->pagination->getChaptersForBook($book),
                'page' => $page
            ]
        );
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'book/list.html.twig',
            ['books' => $this->pagination->getStandalone($page), 'page' => $page]
        );
    }

    public function deleteAction(Book $book, $page)
    {
        $this->manager->remove($book);
        $this->manager->flush();

        return new RedirectResponse(
            $this->router->generate('app_book_list', ['page' => $page])
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
