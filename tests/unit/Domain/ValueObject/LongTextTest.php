<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\ValueObject;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Talesweaver\Domain\ValueObject\LongText;

class LongTextTest extends TestCase
{
    public function testEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The long text needs at least 1 character.');
        new LongText('');
    }
}
