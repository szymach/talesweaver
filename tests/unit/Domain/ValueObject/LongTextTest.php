<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Talesweaver\Domain\ValueObject\LongText;

class LongTextTest extends TestCase
{
    public function testNullValue(): void
    {
        $this->assertNull(LongText::fromNullableString(null));
    }

    public function testEmptyString()
    {
        $this->assertNull(LongText::fromNullableString('  '));
    }

    public function testEmptyHtmlString()
    {
        $this->assertNull(LongText::fromNullableString(
            '<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>'
        ));
    }

    public function testSuccessfullCreation()
    {
        $longText = LongText::fromNullableString('some text');
        $this->assertEquals('some text', (string) $longText);
    }
}
