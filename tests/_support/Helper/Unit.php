<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Helper;

use Codeception\Module;

class Unit extends Module
{
    public function createTooLongString(): string
    {
        return bin2hex(random_bytes(128));
    }
}
