<?php


namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\ValueObject\NIF;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class NIFType extends StringType
{
    public function getName(): string
    {
        return 'nif';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return new NIF($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}