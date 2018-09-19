<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Chapter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\RouterInterface;
use Talesweaver\Application\Command\Scene\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Scene\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Chapter;
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
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

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
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        RouterInterface $router
    ) {
        $this->responseFactory = $responseFactory;
        $this->pagination = $pagination;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, Chapter $chapter, int $page): ResponseInterface
    {
        return $this->responseFactory->toJson([
            'list' => $this->htmlContent->fromTemplate(
                'chapter/scenes/list.html.twig',
                [
                    'chapterId' => $chapter->getId(),
                    'sceneForm' => $this->createSceneForm($request, $chapter),
                    'scenes' => $this->pagination->getResults($chapter, $page, 3),
                    'page' => $page
                ]
            )
        ]);
    }

    private function createSceneForm(ServerRequestInterface $request, Chapter $chapter): FormViewInterface
    {
        return $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($chapter),
            ['action' => $this->router->generate('scene_create')]
        )->createView();
    }
}
