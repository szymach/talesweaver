<?php

declare(strict_types=1);

namespace Talesweaver\Application\Data;

use Psr\Http\Message\ServerRequestInterface;
use Talesweaver\Application\Session\Session;
use Talesweaver\Domain\ValueObject\Sort;

final class Sortable
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function setFromRequest(ServerRequestInterface $request): void
    {
        $key = $this->createKey($request->getAttribute('list'));
        $field = $request->getAttribute('field');
        $direction = $request->getAttribute('direction');
        if (true === $this->shouldBeCleared($key, $field, $direction)) {
            $this->session->remove($key);
            return;
        }

        $this->session->set($key, new Sort($field, $direction));
    }

    public function createFromSession(string $list): ?Sort
    {
        return $this->session->get($this->createKey($list));
    }

    private function shouldBeCleared(string $key, string $field, string $direction): bool
    {
        if (false === $this->session->has($key)) {
            return false;
        }

        /** @var Sort $current */
        $current = $this->session->get($key);
        return $field === $current->getField() && $direction === $current->getDirection();
    }

    private function createKey(string $list): string
    {
        return "talesweaver/sort/{$list}";
    }
}
