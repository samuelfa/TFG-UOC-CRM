<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\User\User;
use App\Domain\User\UserRepository as UserRepositoryInterface;
use App\Domain\ValueObject\NIF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOneByNif(NIF $nif): ?User
    {
        /** @var null|User $entity */
        $entity = $this->findOneBy([
            'nif' => (string) $nif
        ]);

        return $entity;
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
    }
}
