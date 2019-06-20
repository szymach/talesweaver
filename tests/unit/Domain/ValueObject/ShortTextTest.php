<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\ValueObject;

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
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The short text needs at least 1 character.');
        new ShortText('');
    }

    public function testTooLongString(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('The text can be only 255 characters long, but is "256"');
        new ShortText($this->tester->createTooLongString());
    }
}
