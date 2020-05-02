<?php


namespace App\Application\Manager\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\Employee\ManagerNotFound;
use App\Domain\Employee\ManagerRepository;

class ManagerEditService implements TransactionalService
{
    private ManagerRepository $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditManagerDTO $dto */
        $nif = $dto->nif();
        $emailAddress = $dto->emailAddress();

        $manager = $this->repository->findOneByNif($nif);
        if(!$manager){
            throw new ManagerNotFound($nif);
        }

        $currentEmailAddress = $manager->emailAddress();
        if(!$currentEmailAddress->equals($emailAddress) && $this->repository->findOneByEmailAddress($emailAddress)){
            throw new AlreadyExistsEmailAddress($emailAddress);
        }

        $manager->update(
            $emailAddress,
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait(),
            $dto->password()
        );

        $this->repository->save($manager);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditManagerDTO::class;
    }
}