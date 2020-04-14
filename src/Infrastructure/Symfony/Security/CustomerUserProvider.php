<?php


namespace App\Infrastructure\Symfony\Security;



use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\InvalidEmailAddressException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CustomerUserProvider implements UserProviderInterface
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loadUserByUsername(string $username): Customer
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

    public function refreshUser(UserInterface $user): Customer
    {
        if (!$user instanceof Customer) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return Customer::class === $class;
    }
}