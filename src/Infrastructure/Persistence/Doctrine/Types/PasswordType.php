<?php


namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\ValueObject\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class PasswordType extends StringType
{
    public function getName(): string
    {
        return 'password';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return new Password($value);
    }
}