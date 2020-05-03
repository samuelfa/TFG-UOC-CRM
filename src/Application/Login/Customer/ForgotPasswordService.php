<?php


namespace App\Application\Login\Customer;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Customer\CustomerRepository;
use App\Domain\EmailAddressNotFound;
use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\Login\ForgotPasswordEmailEventDispatcher;
use App\Domain\Login\ForgotPasswordEmailRepository;

class ForgotPasswordService implements TransactionalService
{
    private ForgotPasswordEmailRepository $repository;
    private ForgotPasswordEmailEventDispatcher $dispatcher;
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        ForgotPasswordEmailRepository $repository,
        ForgotPasswordEmailEventDispatcher $dispatcher
    )
    {
        $this->customerRepository = $customerRepository;
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var ForgotPasswordDTO $dto */
        $emailAddress = $dto->emailAddress();

        $customer = $this->customerRepository->findOneByEmailAddress($emailAddress);
        if(!$customer){
            throw new EmailAddressNotFound($emailAddress);
        }

        $token = ForgotPasswordEmail::create($emailAddress);
        $this->repository->save($token);

        $this->dispatcher->created($token, true);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return ForgotPasswordDTO::class;
    }
}