<?php


namespace App\Domain;


interface Repository
{
    public function flush(): void;
}