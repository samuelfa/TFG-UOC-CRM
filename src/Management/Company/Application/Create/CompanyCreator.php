<?php

declare(strict_types = 1);

namespace App\Management\Company\Application\Create;

use App\Management\Company\Domain\Company;
use App\Management\Company\Domain\CompanyRepository;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\ValueObject\EmailAddress;
use App\Shared\Domain\ValueObject\Uuid;

final class CompanyCreator
{
    private CompanyRepository $repository;
    private EventBus         $bus;

    public function __construct(CompanyRepository $repository, EventBus $bus)
    {
        $this->repository = $repository;
        $this->bus        = $bus;
    }

    public function __invoke(Uuid $id, string $name, EmailAddress $emailAddress): void
    {
        $company = Company::create($id, $name, $emailAddress);

        $this->repository->save($company);
        $this->bus->publish(...$company->pullDomainEvents());
    }
}
