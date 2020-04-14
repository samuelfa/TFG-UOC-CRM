<?php


namespace App\Infrastructure\Symfony\Security;


use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\NIF;
use App\Infrastructure\Symfony\Factory\URLFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AuthenticateAfterRegister
{
    private GuardAuthenticatorHandler $authenticatorHandler;
    private Authenticator $authenticator;
    private WorkerRepository $repository;
    private URLFactory $URLFactory;

    public function __construct(
        GuardAuthenticatorHandler $authenticatorHandler,
        Authenticator $authenticator,
        WorkerRepository $repository,
        URLFactory $URLFactory
    )
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
        $this->repository = $repository;
        $this->URLFactory = $URLFactory;
    }

    public function authenticate(NIF $nif, string $namespace, Request $request): string
    {
        $user = $this->repository->findOneByNif($nif);
        if(!$user){
            throw new \RuntimeException('User not found');
        }

        $this->authenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $this->authenticator, 'main');

        return $this->URLFactory->generate($namespace);
    }

}