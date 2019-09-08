<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Domain\Administrator;

final class ActivateAdministrator
{
    /**
     * @var Administrator
     */
    private $administrator;

    public function __construct(Administrator $administrator)
    {
        $this->administrator = $administrator;
    }

    public function getAdministrator(): Administrator
    {
        return $this->administrator;
    }
}
