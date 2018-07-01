<?php

declare(strict_types=1);

namespace Application\Messages;

class EditionSuccessMessage extends Message
{
    public function __construct(
        string $translationKeyRoot,
        array $translationParameters = [],
        string $type = null
    ) {
        parent::__construct(
            sprintf('%s.alert.edit', $translationKeyRoot),
            $translationParameters,
            $type ?? 'success'
        );
    }
}
