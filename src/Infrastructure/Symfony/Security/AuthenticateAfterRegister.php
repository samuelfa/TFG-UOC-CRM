<?php


namespace App\Infrastructure\Symfony\Security;


use App\Domain\Employee\Employee;
use App\Domain\Employee\ManagerRepository;
use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\NIF;
use App\Infrastructure\Symfony\Factory\URLFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AuthenticateAfterRegister
{
    private GuardAuthenticatorHandler $authenticatorHandler;
    private Authenticator $authenticator;
    private WorkerRepository $workerRepository;
    private ManagerRepository $managerRepository;
    private URLFactory $URLFactory;

    public function __construct(
        GuardAuthenticatorHandler $authenticatorHandler,
        Authenticator $authenticator,
        WorkerRepository $workerRepository,
        ManagerRepository $managerRepository,
        URLFactory $URLFactory
    )
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
        $this->workerRepository = $workerRepository;
        $this->managerRepository = $managerRepository;
        $this->URLFactory = $URLFactory;
    }

    public function authenticate(NIF $nif, string $namespace, Request $request): string
    {
        $user = $this->findOneByNif($nif);

        $this->authenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $this->authenticator, 'main');
        return $this->URLFactory->generate($namespace);
    }

    private function findOneByNif(NIF $nif): Employee
    {
        $user = $this->workerRepository->findOneByNif($nif);
        if($user){
            return $user;
        }

        $user = $this->managerRepository->findOneByNif($nif);
        if($user){
            return $user;
        }

        throw new \RuntimeException('User not found');
    }

}