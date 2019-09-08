<?php

declare(strict_types=1);

namespace Talesweaver\Application\Query\Security;

use Talesweaver\Application\Bus\QueryHandlerInterface;
use Talesweaver\Domain\Administrator;
use Talesweaver\Domain\Administrators;

final class AdministratorByEmailHandler implements QueryHandlerInterface
{
    /**
     * @var Administrators
     */
    private $administrators;

    public function __construct(Administrators $administrators)
    {
        $this->administrators = $administrators;
    }

    public function __invoke(AdministratorByEmail $query): ?Administrator
    {
        return $this->administrators->findByEmail($query->getEmail());
    }
}
