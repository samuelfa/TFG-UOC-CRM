<?php


namespace App\Domain\Company;


use App\Application\Company\Create\CreateCompanyDTO;

interface CompanyEventDispatcher
{
    public function created(CreateCompanyDTO $dto): void;
}