<?php


namespace App\Domain\Manager;


use App\Domain\ValueObject\Role;

class RoleManager extends Role
{
    private const VALUE = 1;

    public static function create(): self
    {
        return new self(self::VALUE);
    }
}