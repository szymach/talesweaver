<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Character\Create\Command;
use Talesweaver\Application\Command\Character\Create\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Character\Create;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Scene\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Scene;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class CreateController
{
    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var AuthorContext
     */
    private $authorContext;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var FormHandlerFactoryInterface
     */
    private $formHandlerFactory;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(
        QueryBus $queryBus,
        AuthorContext $authorContext,
        ResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $scene = $this->getScene($request->getAttribute('id'));
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Create::class,
            new DTO($scene),
            [
                'action' => $this->urlGenerator->generate('character_new', ['id' => $scene->getId()]),
                'sceneId' => $scene->getId()
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($scene, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial\simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'character.header.new']
            )
        ], false === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processFormDataAndRedirect(Scene $scene, DTO $dto): ResponseInterface
    {
        $description = $dto->getName();
        $avatar = $dto->getAvatar();
        $this->commandBus->dispatch(new Command(
            $scene,
            Uuid::uuid4(),
            new ShortText($dto->getName()),
            null !== $description ? new LongText($description) : null,
            null !== $avatar ? new File($avatar) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getScene(?string $id): Scene
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No scene id!');
        }

        $uuid = Uuid::fromString($id);
        $scene = $this->queryBus->query(new ById($uuid));
        if (false === $scene instanceof Scene
            || $this->authorContext->getAuthor() !== $scene->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No scene for id "%s"!', $uuid->toString()));
        }

        return $scene;
    }
}
