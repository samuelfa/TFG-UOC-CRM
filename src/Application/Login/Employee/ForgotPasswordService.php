<?php


namespace App\Application\Login\Employee;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\EmailAddressNotFound;
use App\Domain\Employee\ManagerRepository;
use App\Domain\Employee\WorkerRepository;
use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\Login\ForgotPasswordEmailEventDispatcher;
use App\Domain\Login\ForgotPasswordEmailRepository;

class ForgotPasswordService implements TransactionalService
{
    private WorkerRepository $workerRepository;
    private ManagerRepository $managerRepository;
    private ForgotPasswordEmailRepository $repository;
    private ForgotPasswordEmailEventDispatcher $dispatcher;

    public function __construct(
        WorkerRepository $workerRepository,
        ManagerRepository $managerRepository,
        ForgotPasswordEmailRepository $repository,
        ForgotPasswordEmailEventDispatcher $dispatcher
    )
    {
        $this->workerRepository  = $workerRepository;
        $this->managerRepository = $managerRepository;
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var ForgotPasswordDTO $dto */
        $emailAddress = $dto->emailAddress();

        $employee = $this->workerRepository->findOneByEmailAddress($emailAddress);
        if(!$employee){
            $employee = $this->managerRepository->findOneByEmailAddress($emailAddress);
        }

        if(!$employee){
            throw new EmailAddressNotFound($emailAddress);
        }

        $token = $this->repository->findOneByEmailAddress($emailAddress);
        if(!$token){
            $token = ForgotPasswordEmail::create($emailAddress);
        } else {
            $token->regenerate();
        }

        $this->repository->save($token);

        $this->dispatcher->created($token, false);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return ForgotPasswordDTO::class;
    }
}