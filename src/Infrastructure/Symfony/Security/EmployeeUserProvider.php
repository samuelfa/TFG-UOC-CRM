<?php


namespace App\Infrastructure\Symfony\Security;


use App\Domain\Employee\Employee;
use App\Domain\Employee\ManagerRepository;
use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\InvalidEmailAddressException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class EmployeeUserProvider implements UserProviderInterface
{
    private WorkerRepository $workerRepository;
    private ManagerRepository $managerRepository;

    public function __construct(WorkerRepository $workerRepository, ManagerRepository $managerRepository)
    {
        $this->workerRepository = $workerRepository;
        $this->managerRepository = $managerRepository;
    }

    public function loadUserByUsername(string $username): Employee
    {
        try {
            $emailAddress = new EmailAddress($username);
        } catch (InvalidEmailAddressException $exception){
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        $worker = $this->workerRepository->findOneByEmailAddress($emailAddress);
        if($worker){
            return $worker;
        }

        $manager = $this->managerRepository->findOneByEmailAddress($emailAddress);
        if($manager){
            return $manager;
        }

        throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    public function refreshUser(UserInterface $user): Employee
    {
        if (!$user instanceof Employee) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return is_subclass_of($class, Employee::class);
    }
}