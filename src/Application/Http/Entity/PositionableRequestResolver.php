<?php

declare(strict_types=1);

namespace Talesweaver\Application\Http\Entity;

use Assert\Assertion;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

final class PositionableRequestResolver
{
    public function resolve(ServerRequestInterface $request): array
    {
        $data = $request->getParsedBody();
        Assertion::isArray($data, 'Could not decode request contents');

        return array_map(
            function (array $item): array {
                Assertion::numeric($item['position'], "Incorrect position for id {$item['id']}");
                return ['id' => Uuid::fromString($item['id']), 'position' => (int) $item['position']];
            },
            $data
        );
    }
}
