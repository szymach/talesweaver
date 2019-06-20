<?php

declare(strict_types=1);

namespace Talesweaver\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Talesweaver\Domain\ValueObject\LongText;

class LongTextTest extends TestCase
{
    public function testNullValue(): void
    {
        self::assertNull(LongText::fromNullableString(null));
    }

    public function testEmptyString()
    {
        self::assertNull(LongText::fromNullableString('  '));
    }

    public function testEmptyHtmlString()
    {
        self::assertNull(LongText::fromNullableString(
            '<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>'
        ));
    }

    public function testSuccessfullCreation()
    {
        $longText = LongText::fromNullableString('some text');
        self::assertEquals('some text', (string) $longText);
    }
}
