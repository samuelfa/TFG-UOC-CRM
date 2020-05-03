<?php


namespace App\Application\Login\Customer;


use App\Application\DTO;
use App\Application\Login\TokenNotFound;
use App\Application\TransactionalService;
use App\Domain\Customer\CustomerRepository;
use App\Domain\EmailAddressNotFound;
use App\Domain\Login\ForgotPasswordEmailRepository;

class RestorePasswordService implements TransactionalService
{
    private CustomerRepository $customerRepository;
    private ForgotPasswordEmailRepository $repository;

    public function __construct(
        CustomerRepository $customerRepository,
        ForgotPasswordEmailRepository $repository
    )
    {
        $this->customerRepository = $customerRepository;
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

        $customer = $this->customerRepository->findOneByEmailAddress($token->emailAddress());
        if(!$customer){
            throw new EmailAddressNotFound($token->emailAddress());
        }

        $this->repository->remove($token);
        $customer->setPassword($password);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return RestorePasswordDTO::class;
    }
}