<?php

declare(strict_types=1);

namespace Talesweaver\Application\Scene\Messages;

use Talesweaver\Application\Messages\Message;

class RemovedFromSceneSuccessMessage extends Message
{
    public function __construct(
        string $translationKeyRoot,
        array $translationParameters = [],
        string $type = null
    ) {
        parent::__construct(
            sprintf('%s.alert.removed_from_scene', $translationKeyRoot),
            $translationParameters,
            $type ?? 'success'
        );
    }
}
