<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\ValueObject;

use Assert\InvalidArgumentException;
use Codeception\Test\Unit;
use Talesweaver\Domain\ValueObject\ShortText;
use Talesweaver\Tests\UnitTester;

class ShortTextTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The short text needs at least 1 character.');
        new ShortText('');
    }

    public function testTooLongString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The text can be only 255 characters long, but is "256"');
        new ShortText($this->tester->createTooLongString());
    }
}
