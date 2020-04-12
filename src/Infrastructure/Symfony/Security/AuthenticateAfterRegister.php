<?php


namespace App\Infrastructure\Symfony\Security;


use App\Domain\User\UserRepository;
use App\Domain\ValueObject\NIF;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AuthenticateAfterRegister
{
    private GuardAuthenticatorHandler $authenticatorHandler;
    private Authenticator $authenticator;
    private UserRepository $repository;

    public function __construct(
        GuardAuthenticatorHandler $authenticatorHandler,
        Authenticator $authenticator,
        UserRepository $repository
    )
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
        $this->repository = $repository;
    }

    public function authenticate(NIF $nif, Request $request): void
    {
        //TODO: Review why is not getting the entity just created
        $user = $this->repository->findOneByNif($nif);
        if(!$user){
            throw new \RuntimeException('User not found');
        }
        $securityUser = User::createFromUser($user);
        $this->authenticatorHandler->authenticateUserAndHandleSuccess($securityUser, $request, $this->authenticator, 'main');
    }

}