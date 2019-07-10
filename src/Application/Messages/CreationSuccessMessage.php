<?php

declare(strict_types=1);

namespace Talesweaver\Application\Messages;

final class CreationSuccessMessage extends Message
{
    public function __construct(
        string $translationKeyRoot,
        array $translationParameters = [],
        string $type = null
    ) {
        parent::__construct(
            "{$translationKeyRoot}.alert.created",
            $translationParameters,
            $type ?? 'success'
        );
    }
}
