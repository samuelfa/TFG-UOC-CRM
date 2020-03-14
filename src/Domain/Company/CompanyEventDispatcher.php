<?php


namespace App\Domain\Company;


interface CompanyEventDispatcher
{
    public function created(string $namespace): void;
}