<?php


namespace App\Infrastructure\Symfony\Security;


use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\NIF;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AuthenticateAfterRegister
{
    private GuardAuthenticatorHandler $authenticatorHandler;
    private Authenticator $authenticator;
    private WorkerRepository $repository;

    public function __construct(
        GuardAuthenticatorHandler $authenticatorHandler,
        Authenticator $authenticator,
        WorkerRepository $repository
    )
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
        $this->repository = $repository;
    }

    public function authenticate(NIF $nif, Request $request): void
    {
        $user = $this->repository->findOneByNif($nif);
        if(!$user){
            throw new \RuntimeException('User not found');
        }
        $securityUser = User::createFromWorker($user);
        $this->authenticatorHandler->authenticateUserAndHandleSuccess($securityUser, $request, $this->authenticator, 'main');
    }

}