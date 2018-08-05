<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Talesweaver\Domain\ValueObject\ShortText as ShortTextValueObject;

class ShortText extends Type
{
    public const NAME = 'short_text';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL(['length' => 255, 'fixed' => false]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ShortTextValueObject
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (true === $value instanceof ShortTextValueObject) {
            return $value;
        }

        try {
            $emailAddress = new ShortTextValueObject($value);
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

        if ($value instanceof ShortTextValueObject) {
            return (string) $value;
        }

        try {
            $emailAddress = new ShortTextValueObject($value);
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
