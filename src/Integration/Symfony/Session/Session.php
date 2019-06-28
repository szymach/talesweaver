<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Talesweaver\Application\Session\Session as ApplicationSession;

final class Session implements ApplicationSession
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function has(string $key): bool
    {
        return $this->session->has($key);
    }

    public function set(string $key, $value): void
    {
        $this->session->set($key, $value);
    }

    public function get(string $key, $default = null)
    {
        return $this->session->get($key, $default);
    }

    public function remove(string $key): void
    {
        $this->session->remove($key);
    }
}
