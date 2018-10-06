<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\Entity\CharacterResolver;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Domain\Character;

class DisplayController
{
    /**
     * @var CharacterResolver
     */
    private $characterResolver;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        CharacterResolver $characterResolver,
        QueryBus $queryBus,
        HtmlContent $htmlContent,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->characterResolver = $characterResolver;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $character = $this->characterResolver->fromRequest($request);
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
}
