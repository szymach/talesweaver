<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Scene\Create\DTO;
use Talesweaver\Domain\Chapter;
use Talesweaver\Integration\Symfony\Form\Scene\CreateType;
use Talesweaver\Integration\Symfony\Pagination\Chapter\ScenePaginator;

class ScenesListController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ScenePaginator
     */
    private $pagination;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ScenePaginator $pagination,
        FormFactoryInterface $formFactory,
        HtmlContent $htmlContent,
        RouterInterface $router
    ) {
        $this->responseFactory = $responseFactory;
        $this->pagination = $pagination;
        $this->formFactory = $formFactory;
        $this->htmlContent = $htmlContent;
        $this->router = $router;
    }

    public function createView(Chapter $chapter, int $page): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'chapter/scenes/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'sceneForm' => $this->createSceneForm($chapter),
                    'scenes' => $this->pagination->getResults($chapter, $page, 3),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createSceneForm(Chapter $chapter): FormView
    {
        return $this->formFactory->create(CreateType::class, new DTO($chapter), [
            'action' => $this->router->generate('scene_create')
        ])->createView();
    }
}
