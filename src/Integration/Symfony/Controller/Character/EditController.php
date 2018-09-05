<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Form\FormFactoryInterface;
use Talesweaver\Application\Character\Edit\Command;
use Talesweaver\Application\Character\Edit\DTO;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Domain\Character;
use Talesweaver\Domain\ValueObject\File;
use Talesweaver\Domain\ValueObject\LongText;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Integration\Symfony\Form\Character\EditType;
use Talesweaver\Integration\Symfony\Templating\Character\FormView;

class EditController
{
    /**
     * @var FormView
     */
    private $templating;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MessageBus
     */
    private $commandBus;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        FormView $templating,
        FormFactoryInterface $formFactory,
        MessageBus $commandBus,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request, Character $character): ResponseInterface
    {
        $form = $this->formFactory->create(EditType::class, new DTO($character), [
            'action' => $this->responseFactory->generate('character_edit', ['id' => $character->getId()]),
            'characterId' => $character->getId()
        ]);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            return $this->processFormDataAndRedirect($character, $form->getData());
        }

        return $this->templating->createView($form, 'character.header.edit');
    }

    private function processFormDataAndRedirect(Character $character, DTO $dto): ResponseInterface
    {
        $description = $dto->getName();
        $avatar = $dto->getAvatar();
        $this->commandBus->handle(new Command(
            $character,
            new ShortText($dto->getName()),
            null !== $description ? new LongText($description) : null,
            null !== $avatar ? new File($avatar) : null
        ));

        return $this->responseFactory->toJson(['success' => true]);
    }
}
