<?php


namespace App\Domain\Company;


class CompanyNotFound extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("Company {$nif} not found");
    }

}