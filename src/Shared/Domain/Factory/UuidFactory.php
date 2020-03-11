<?php


namespace App\Shared\Domain\Factory;

use App\Shared\Domain\ValueObject\Uuid;

interface UuidFactory
{
    public function create(): Uuid;
}