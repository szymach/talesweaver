<?php

declare(strict_types=1);

namespace Talesweaver\Application\Session;

use Assert\Assertion;

final class Flash
{
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const ERROR = 'error';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $domain;

    public function __construct(string $type, string $key, array $parameters, string $domain = 'messages')
    {
        Assertion::inArray($type, [self::SUCCESS, self::WARNING, self::ERROR]);
        $this->type = $type;
        $this->key = $key;
        $this->parameters = $parameters;
        $this->domain = $domain;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
