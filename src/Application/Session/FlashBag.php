<?php

declare(strict_types=1);

namespace Talesweaver\Application\Session;

interface FlashBag
{
    public function add(Flash $flash);
}
