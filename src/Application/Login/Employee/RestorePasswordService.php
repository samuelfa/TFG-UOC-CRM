<?php


namespace App\Application\Login\Employee;


use App\Application\DTO;
use App\Application\Login\TokenNotFound;
use App\Application\TransactionalService;
use App\Domain\EmailAddressNotFound;
use App\Domain\Employee\ManagerRepository;
use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerRepository;
use App\Domain\Login\ForgotPasswordEmailRepository;

class RestorePasswordService implements TransactionalService
{
    private WorkerRepository $workerRepository;
    private ManagerRepository $managerRepository;
    private ForgotPasswordEmailRepository $repository;

    public function __construct(
        WorkerRepository $workerRepository,
        ManagerRepository $managerRepository,
        ForgotPasswordEmailRepository $repository
    )
    {
        $this->workerRepository  = $workerRepository;
        $this->managerRepository = $managerRepository;
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var RestorePasswordDTO $dto */
        $value = $dto->value();
        $password = $dto->password();

        $token = $this->repository->findOneByToken($value);
        if(!$token){
            throw new TokenNotFound($value);
        }

        $employee = $this->workerRepository->findOneByEmailAddress($token->emailAddress());
        if(!$employee){
            $employee = $this->managerRepository->findOneByEmailAddress($token->emailAddress());
        }

        if(!$employee){
            throw new EmailAddressNotFound($token->emailAddress());
        }

        $this->repository->remove($token);
        $employee->setPassword($password);
        if($employee instanceof Worker){
            $this->workerRepository->save($employee);
        } else {
            $this->managerRepository->save($employee);
        }

        return $dto;
    }

    public function subscribeTo(): string
    {
        return RestorePasswordDTO::class;
    }
}