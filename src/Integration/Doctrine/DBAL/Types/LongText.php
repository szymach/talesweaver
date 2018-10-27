<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Talesweaver\Domain\ValueObject\LongText as LongTextValueObject;

class LongText extends Type
{
    public const NAME = 'long_text';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?LongTextValueObject
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (true === $value instanceof LongTextValueObject) {
            return $value;
        }

        try {
            $longText = LongTextValueObject::fromNullableString($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $longText;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof LongTextValueObject) {
            return (string) $value;
        }

        try {
            $longText = LongTextValueObject::fromNullableString($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return (string) $longText;
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
