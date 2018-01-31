<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueDTO extends Constraint
{
    public $message = 'unqiue_dto.exists';

    /**
     * @var array
     */
    public $fields = [];

    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $repositoryMethod = 'findOneBy';

    /**
     * @var string
     */
    public $path;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getRequiredOptions()
    {
        return ['fields'];
    }
}