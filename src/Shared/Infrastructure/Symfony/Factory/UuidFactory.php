<?php


namespace App\Shared\Infrastructure\Symfony\Factory;


use App\Shared\Domain\ValueObject\Uuid;

final class UuidFactory implements \App\Shared\Domain\Factory\UuidFactory
{
    public function create(): Uuid
    {
        $value = uuid_create(UUID_TYPE_RANDOM);
        return new Uuid($value);
    }
}