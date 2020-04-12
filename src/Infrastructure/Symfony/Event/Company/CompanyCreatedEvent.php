<?php


namespace App\Infrastructure\Symfony\Event\Company;

use App\Application\Company\Create\CreateCompanyDTO;
use App\Domain\Event\Event as EventDomain;
use Symfony\Contracts\EventDispatcher\Event;

class CompanyCreatedEvent extends Event implements EventDomain
{
    private CreateCompanyDTO $dto;

    public function __construct(CreateCompanyDTO $dto)
    {
        $this->dto = $dto;
    }

    public function dto(): CreateCompanyDTO
    {
        return $this->dto;
    }
}