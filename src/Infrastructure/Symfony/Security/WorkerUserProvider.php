<?php


namespace App\Infrastructure\Symfony\Security;


use App\Domain\Employee\Manager;
use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\InvalidEmailAddressException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WorkerUserProvider implements UserProviderInterface
{
    private WorkerRepository $repository;

    public function __construct(WorkerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loadUserByUsername(string $username): Worker
    {
        try {
            $emailAddress = new EmailAddress($username);
        } catch (InvalidEmailAddressException $exception){
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        $user = $this->repository->findOneByEmailAddress($emailAddress);
        if(!$user){
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }
        return $user;
    }

    public function refreshUser(UserInterface $user): Worker
    {
        if (!$user instanceof Worker) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return
            Worker::class === $class ||
            Manager::class === $class
        ;
    }
}