<?php


namespace App\Application\Manager\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerRepository;

class ManagerCreateService implements TransactionalService
{
    private ManagerRepository $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var CreateManagerDTO $dto */
        $nif = $dto->nif();

        if($this->repository->findOneByNif($nif)){
            throw new AlreadyExistsNif($nif);
        }

        $manager = Manager::create(
            $nif,
            $dto->emailAddress(),
            $dto->password(),
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait()
        );

        $this->repository->save($manager);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return CreateManagerDTO::class;
    }
}