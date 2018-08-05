<?php

declare(strict_types=1);

namespace Talesweaver\Domain\Tests\ValueObject;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Talesweaver\Domain\ValueObject\Email;

class EmailTest extends TestCase
{
    /**
     * @dataProvider incorrectEmails
     */
    public function testIncorrectEmail(string $value, string $exceptionMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        new Email($value);
    }

    public function incorrectEmails(): array
    {
        return [
            ['not an email', 'Value "not an email" was expected to be a valid e-mail address.'],
            ['', 'Value "" was expected to be a valid e-mail address.']
        ];
    }
}
