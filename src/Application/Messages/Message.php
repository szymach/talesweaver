<?php

declare(strict_types=1);

namespace Talesweaver\Application\Messages;

class Message
{
    public const SUCCESS = 'success';

    /**
     * @var string
     */
    private $translationKey;

    /**
     * @var array
     */
    private $translationParameters;

    /**
     * @var string
     */
    private $type;

    public function __construct(string $translationKey, array $translationParameters, string $type)
    {
        $this->translationKey = $translationKey;
        $this->translationParameters = $translationParameters;
        $this->type = $type;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    public function getTranslationParameters(): array
    {
        return $this->translationParameters;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
