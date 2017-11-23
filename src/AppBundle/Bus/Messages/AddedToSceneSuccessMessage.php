<?php

declare(strict_types=1);

namespace AppBundle\Bus\Messages;

class AddedToSceneSuccessMessage extends Message
{
    public function __construct(
        string $translationKeyRoot,
        array $translationParameters = [],
        string $type = null
    ) {
        parent::__construct(
            sprintf('%s.alert.added_to_scene', $translationKeyRoot),
            $translationParameters,
            $type ?? 'success'
        );
    }
}
