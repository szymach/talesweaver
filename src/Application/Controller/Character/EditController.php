<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Character\Edit\Command;
use Talesweaver\Application\Command\Character\Edit\DTO;
use Talesweaver\Application\Form\FormHandlerFactoryInterface;
use Talesweaver\Application\Form\Type\Character\Edit;
use Talesweaver\Application\Http\ApiResponseFactoryInterface;
use Talesweaver\Application\Http\Entity\CharacterResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\UrlGenerator;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;

class EditController
{
    /**
     * @var CharacterResolver
     */
    private $characterResolver;

    /**
     * @var ApiResponseFactoryInterface
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
        CharacterResolver $characterResolver,
        ApiResponseFactoryInterface $responseFactory,
        FormHandlerFactoryInterface $formHandlerFactory,
        HtmlContent $htmlContent,
        CommandBus $commandBus,
        UrlGenerator $urlGenerator
    ) {
        $this->characterResolver = $characterResolver;
        $this->responseFactory = $responseFactory;
        $this->formHandlerFactory = $formHandlerFactory;
        $this->htmlContent = $htmlContent;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $character = $this->characterResolver->fromRequest($request);
        $formHandler = $this->formHandlerFactory->createWithRequest(
            $request,
            Edit::class,
            new DTO($character),
            [
                'action' => $this->urlGenerator->generate('character_edit', ['id' => $character->getId()]),
                'characterId' => $character->getId()
            ]
        );

        if (true === $formHandler->isSubmissionValid()) {
            return $this->processFormDataAndRedirect($character, $formHandler->getData());
        }

        return $this->responseFactory->form(
            'partial\simpleForm.html.twig',
            ['form' => $formHandler->createView(), 'title' => 'character.header.edit'],
            $formHandler->displayErrors()
        );
    }

    private function processFormDataAndRedirect(Character $character, DTO $dto): ResponseInterface
    {
        $avatar = $dto->getAvatar();
        $this->commandBus->dispatch(new Command(
            $character,
            new ShortText($dto->getName()),
            LongText::fromNullableString($dto->getDescription()),
            null !== $avatar ? new File($avatar) : null
        ));

        return $this->responseFactory->success();
    }
}
