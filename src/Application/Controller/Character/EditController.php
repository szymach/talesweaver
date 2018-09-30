<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Command\Character\Edit\Command;
use Talesweaver\Application\Command\Character\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Character\Edit;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Application\Query\Character\ById;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
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
        $character = $this->getCharacter($request->getAttribute('id'));
        $formHandler = $this->formHandlerFactory->createWithRequest($request, Edit::class, new DTO($character), [
            'action' => $this->urlGenerator->generate('character_edit', ['id' => $character->getId()]),
            'characterId' => $character->getId()
        ]);

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($character, $formHandler->getData());
        }

        return $this->responseFactory->toJson([
            'form' => $this->htmlContent->fromTemplate(
                'partial\simpleForm.html.twig',
                ['form' => $formHandler->createView(), 'title' => 'character.header.edit']
            )
        ], false === $formHandler->displayErrors() ? 200 : 400);
    }

    private function processFormDataAndRedirect(Character $character, DTO $dto): ResponseInterface
    {
        $description = $dto->getName();
        $avatar = $dto->getAvatar();
        $this->commandBus->dispatch(new Command(
            $character,
            new ShortText($dto->getName()),
            null !== $description ? new LongText($description) : null,
            null !== $avatar ? new File($avatar) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }

    private function getCharacter(?string $id): Character
    {
        if (null === $id) {
            throw $this->responseFactory->notFound('No character id!');
        }

        $uuid = Uuid::fromString($id);
        $character = $this->queryBus->query(new ById($uuid));
        if (false === $character instanceof Character
            || $this->authorContext->getAuthor() !== $character->getCreatedBy()
        ) {
            throw $this->responseFactory->notFound(sprintf('No character for id "%s"!', $uuid->toString()));
        }

        return $character;
    }
}
