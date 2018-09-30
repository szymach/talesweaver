<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Character\ById;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Application\Security\AuthorContext;
use Talesweaver\Domain\Character;

class DisplayController
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
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        QueryBus $queryBus,
        AuthorContext $authorContext,
        HtmlContent $htmlContent,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->queryBus = $queryBus;
        $this->authorContext = $authorContext;
        $this->htmlContent = $htmlContent;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $character = $this->getCharacter($request->getAttribute('id'));
        return $this->responseFactory->toJson([
            'display' => $this->htmlContent->fromTemplate(
                'scene\characters\display.html.twig',
                [
                    'character' => $character,
                    'timeline' => $this->queryBus->query(
                        new ForEntity($character->getId(), Character::class)
                    )
                ]
            )
        ]);
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
