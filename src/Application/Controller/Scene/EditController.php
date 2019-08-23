<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Scene;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Scene\Edit\DTO as EditDTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\FormViewInterface;
use Talesweaver\Application\Form\Type\Scene\Edit;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\SceneResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Chapter\ScenesPage;
use Talesweaver\Application\Query\Character\CharactersPage;
use Talesweaver\Application\Query\Event\EventsPage;
use Talesweaver\Application\Query\Item\ItemsPage;
use Talesweaver\Application\Query\Location\LocationsPage;
use Talesweaver\Application\Query\Scene\PublicationsPage;
use Talesweaver\Domain\Scene;
use function is_xml_http_request;

final class EditController
{
    /**
     * @var SceneResolver
     */
    private $sceneResolver;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var ApiResponseFactoryInterface
     */
    private $apiResponseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(
        SceneResolver $sceneResolver,
        ResponseFactoryInterface $responseFactory,
        ApiResponseFactoryInterface $apiResponseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        QueryBus $queryBus,
        CommandBus $commandBus
    ) {
        $this->sceneResolver = $sceneResolver;
        $this->responseFactory = $responseFactory;
        $this->apiResponseFactory = $apiResponseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->sceneResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new EditDTO($scene),
            ['sceneId' => $scene->getId()]
        );

        $isAjax = is_xml_http_request($request);
        if (true === $formHandler->isSubmissionValid()) {
            $response = $this->processFormDataAndRedirect($scene, $formHandler->getData(), $isAjax);
        } elseif (true === $formHandler->displayErrors() && true === $isAjax) {
            $response = $this->apiResponseFactory->form(
                'scene/form/editForm.html.twig',
                ['form' => $formHandler->createView()],
                true
            );
        } else {
            $response = $this->responseFactory->fromTemplate(
                'scene/editForm.html.twig',
                $this->getViewParameters($scene, $formHandler->createView())
            );
        }

        return $response;
    }

    private function processFormDataAndRedirect(Scene $scene, EditDTO $dto, bool $isAjax): ResponseInterface
    {
        $this->commandBus->dispatch($dto->toCommand($scene, $isAjax));

        return true === $isAjax
            ? $this->apiResponseFactory->success()
            : $this->responseFactory->redirectToRoute('scene_edit', ['id' => $scene->getId()])
        ;
    }

    private function getViewParameters(Scene $scene, FormViewInterface $form): array
    {
        $parameters = [
            'form' => $form,
            'sceneId' => $scene->getId(),
            'title' => $scene->getTitle(),
            'characters' => $this->queryBus->query(new CharactersPage($scene, 1)),
            'items' => $this->queryBus->query(new ItemsPage($scene, 1)),
            'locations' => $this->queryBus->query(new LocationsPage($scene, 1)),
            'events' => $this->queryBus->query(new EventsPage($scene, 1)),
            'publications' => $this->queryBus->query(new PublicationsPage($scene, 1)),
        ];

        if (null !== $scene->getChapter()) {
            $chapter = $scene->getChapter();
            $parameters['chapterTitle'] = $chapter->getTitle();
            $parameters['chapterId'] = $chapter->getId();
            $parameters['relatedScenes'] = $this->queryBus->query(new ScenesPage($chapter, 1));
            if (null !== $chapter->getBook()) {
                $book = $chapter->getBook();
                $parameters['bookTitle'] = $book->getTitle();
                $parameters['bookId'] = $book->getId();
            }
        } else {
            $parameters['chapterTitle'] = null;
            $parameters['chapterId'] = null;
            $parameters['relatedScenes'] = [];
        }

        return $parameters;
    }
}
