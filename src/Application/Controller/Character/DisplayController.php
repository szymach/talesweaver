<?php

declare(strict_types=1);

namespace Talesweaver\Application\Controller\Character;

use Psr\Http\Message\ResponseInterface;
use Talesweaver\Application\Bus\QueryBus;
use Talesweaver\Application\Http\HtmlContent;
use Talesweaver\Application\Http\ResponseFactoryInterface;
use Talesweaver\Application\Query\Timeline\ForEntity;
use Talesweaver\Domain\Character;

class DisplayController
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var HtmlContent
     */
    private $htmlContent;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        QueryBus $queryBus,
        HtmlContent $htmlContent
    ) {
        $this->responseFactory = $responseFactory;
        $this->queryBus = $queryBus;
        $this->htmlContent = $htmlContent;
    }

    public function __invoke(Character $character): ResponseInterface
    {
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
