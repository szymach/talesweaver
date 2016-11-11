<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Chapter;

use AppBundle\Entity\Chapter;
use AppBundle\Form\Chapter\ChapterType;
use AppBundle\Pagination\Chapter\StandalonePaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class StandaloneController
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
        StandalonePaginator $pagination,
        ObjectManager $manager,
        RouterInterface $router
    ) {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->pagination = $pagination;
        $this->manager = $manager;
        $this->router = $router;
    }

    public function newAction(Request $request)
    {
        $chapter = new Chapter();
        $form = $this->getForm(ChapterType::class, $chapter);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $this->manager->persist($data);
            $this->manager->flush();

            return new RedirectResponse(
                $this->router->generate('app_standalone_chapter_edit', ['id' => $data->getId()])
            );
        }

        return $this->templating->renderResponse(
            'chapter/standalone/form.html.twig',
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
            'chapter/standalone/form.html.twig',
            [
                'form' => $form->createView(),
                'chapter' => $chapter
            ]
        );
    }

    public function listAction($page)
    {
        return $this->templating->renderResponse(
            'chapter/standalone/list.html.twig',
            ['chapters' => $this->pagination->getResults($page)]
        );
    }

    public function deleteAction(Chapter $chapter, $page)
    {
        $this->manager->remove($chapter);
        $this->manager->flush();

        return new RedirectResponse(
            $this->router->generate('app_standalone_chapter_list', ['page' => $page])
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
