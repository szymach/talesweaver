<?php

declare(strict_types=1);

namespace AppBundle\Bus\Messages;

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
