<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\Chapter;
use AppBundle\Form\Chapter\ChapterType;
use AppBundle\Pagination\Aggregate\ChapterAggregate;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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

    /**
     * @ParamConverter("book", options={"id" = "book_id"})
     */
    public function newAction(Request $request, Book $book = null)
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

    public function editAction(Request $request, Chapter $chapter)
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
                'scenes' => $this->pagination->getScenesForChapter($chapter)
            ]
        );
    }

    /**
     * @ParamConverter("book", options={"id" = "book_id"})
     */
    public function listAction($page, Book $book = null)
    {
        $chapters = $book
            ? $this->pagination->getForBook($book, $page)
            : $this->pagination->getStandalone($page)
        ;
        return $this->templating->renderResponse(
            'chapter/list.html.twig',
            ['chapters' => $chapters]
        );
    }

    public function deleteAction(Chapter $chapter, $page)
    {
        $this->manager->remove($chapter);
        $this->manager->flush();

        return new RedirectResponse(
            $this->router->generate('app_chapter_list', ['page' => $page])
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
