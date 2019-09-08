<?php

declare(strict_types=1);

namespace Talesweaver\Application\Command\Security;

use Talesweaver\Domain\Administrator;

final class AddAdministrator
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
