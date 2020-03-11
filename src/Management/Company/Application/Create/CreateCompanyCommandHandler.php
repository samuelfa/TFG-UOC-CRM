<?php

namespace App\Management\Company\Application\Create;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Factory\UuidFactory;
use App\Shared\Domain\ValueObject\EmailAddress;

final class CreateCompanyCommandHandler implements CommandHandler
{
    private CompanyCreator $creator;
    private UuidFactory $factory;

    public function __construct(CompanyCreator $creator, UuidFactory $factory)
    {
        $this->creator = $creator;
        $this->factory = $factory;
    }

    public function __invoke(CreateCompanyCommand $command): void
    {
        $id           = $this->factory->create();
        $name         = $command->name();
        $emailAddress = new EmailAddress($command->emailAddress());

        $this->creator->__invoke($id, $name, $emailAddress);
    }
}
