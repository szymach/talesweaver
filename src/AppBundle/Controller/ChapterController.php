<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use AppBundle\Form\Chapter\ChapterType;
use AppBundle\Pagination\Chapter\ChapterAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class ChapterController
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
     * @var StandalonePaginator
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
        ChapterAggregate $pagination,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->pagination = $pagination;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function newStandaloneAction(Request $request)
    {
        return $this->handleChapterCreation($request);
    }

    public function newAssignedAction(Request $request, Book $book)
    {
        return $this->handleChapterCreation($request, $book);
    }

    public function editAction(Request $request, Chapter $chapter, $page)
    {
        $form = $this->getForm(ChapterType::class, $chapter);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush();
        }

        return $this->templating->renderResponse(
            'chapter/form.html.twig',
            [
                'form' => $form->createView(),
                'chapter' => $chapter,
                'scenes' => $this->pagination->getScenesForChapter($chapter, $page),
                'page' => $page
            ]
        );
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $this->pagination->getStandalone($page), 'page' => $page]
        );
    }

    public function deleteAction(Chapter $chapter, $page)
    {
        $bookId = $chapter->getBook() ? $chapter->getBook()->getId() : null;
        $this->manager->remove($chapter);
        $this->manager->flush();

        return new RedirectResponse(
            $bookId
            ? $this->router->generate('app_book_edit', ['id' => $bookId])
            : $this->router->generate('app_chapter_list', ['page' => $page])
        );
    }

    public function scenesListAction(Chapter $chapter, $page)
    {
        return new JsonResponse([
            'list' => $this->templating->render(
                'chapter/scenes/list.html.twig',
                [
                    'chapter' => $chapter,
                    'scenes' => $this->pagination->getScenesForChapter($chapter, $page),
                    'page' => $page
                ]
            )
        ]);
    }

    private function handleChapterCreation(Request $request, Book $book = null)
    {
        $chapter = new Chapter();
        if ($book) {
            $chapter->setBook($book);
        }
        $form = $this->getForm(ChapterType::class, $chapter);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $this->manager->persist($data);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_chapter_edit', ['id' => $data->getId()])
            );
        }

        return $this->templating->renderResponse(
            'chapter/form.html.twig',
            ['form' => $form->createView(), 'chapter' => $chapter]
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
