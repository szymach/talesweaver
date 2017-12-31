<?php

declare(strict_types=1);

namespace App\Bus\Messages;

class CreationSuccessMessage extends Message
{
    public function __construct(
        string $translationKeyRoot,
        array $translationParameters = [],
        string $type = null
    ) {
        parent::__construct(
            sprintf('%s.alert.created', $translationKeyRoot),
            $translationParameters,
            $type ?? 'success'
        );
    }
}
