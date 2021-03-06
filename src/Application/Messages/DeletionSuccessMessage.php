<?php

declare(strict_types=1);

namespace Talesweaver\Application\Messages;

class DeletionSuccessMessage extends Message
{
    public function __construct(
        string $translationKeyRoot,
        array $translationParameters = [],
        string $type = null
    ) {
        parent::__construct(
            sprintf('%s.alert.deleted', $translationKeyRoot),
            $translationParameters,
            $type ?? 'success'
        );
    }
}
