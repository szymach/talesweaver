<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Talesweaver\Domain\ValueObject\Email as EmailValueObject;

class Email extends Type
{
    public const NAME = 'email';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL(['length' => 255, 'fixed' => false]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?EmailValueObject
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (true === $value instanceof EmailValueObject) {
            return $value;
        }

        try {
            $emailAddress = new EmailValueObject($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $emailAddress;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof EmailValueObject) {
            return (string) $value;
        }

        try {
            $emailAddress = new EmailValueObject($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return (string) $emailAddress;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
