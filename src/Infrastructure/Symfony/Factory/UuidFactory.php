<?php


namespace App\Infrastructure\Symfony\Factory;


use App\Domain\Factory\UuidFactory as UuidFactoryInterface;
use App\Domain\ValueObject\Uuid;

final class UuidFactory implements UuidFactoryInterface
{
    public function create(): Uuid
    {
        $value = uuid_create(UUID_TYPE_RANDOM);
        return new Uuid($value);
    }
}