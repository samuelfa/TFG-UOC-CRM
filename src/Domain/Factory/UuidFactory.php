<?php


namespace App\Domain\Factory;

use App\Domain\ValueObject\Uuid;

interface UuidFactory
{
    public function create(): Uuid;
}