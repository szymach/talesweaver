<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Administration\Author;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\CommandBus;
use Talesweaver\Application\Command\Security\ResendActivationCode;
use Talesweaver\Application\Http\Entity\AuthorResolver;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Session\Flash;
use Talesweaver\Application\Session\FlashBag;

final class ResendActivationCodeController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var AuthorResolver
     */
    private $authorResolver;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        AuthorResolver $authorResolver,
        CommandBus $commandBus,
        FlashBag $flashBag
    ) {
        $this->responseFactory = $responseFactory;
        $this->authorResolver = $authorResolver;
        $this->commandBus = $commandBus;
        $this->flashBag = $flashBag;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $author = $this->authorResolver->fromRequest($request);
        if (true === $author->isActive()) {
            $this->flashBag->add(new Flash(
                Flash::ERROR,
                'author.already_active',
                ['%email%' => (string) $author->getEmail()],
                'administration'
            ));
        }

        $this->commandBus->dispatch(new ResendActivationCode($author));

        return $this->responseFactory->redirectToRoute('admin_author_list');
    }
}
