<?php


namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\ValueObject\EmailAddress;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SimpleArrayType;

class ArrayEmailAddressType extends SimpleArrayType
{
    public function getName(): string
    {
        return 'array_email_address';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        $list = [];
        foreach ($value as $emailAddressValue){
            $list[] = new EmailAddress($emailAddressValue);
        }
        return $list;
    }
}