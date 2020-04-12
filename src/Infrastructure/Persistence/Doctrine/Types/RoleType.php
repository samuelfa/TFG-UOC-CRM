<?php


namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\ValueObject\Role;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;

class RoleType extends SmallIntType
{
    public function getName(): string
    {
        return 'role';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return new Role((int) $value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}