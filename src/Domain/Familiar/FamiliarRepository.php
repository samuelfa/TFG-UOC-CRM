<?php

namespace App\Domain\Familiar;


interface FamiliarRepository
{
    public function save(Familiar $company): void;
}
