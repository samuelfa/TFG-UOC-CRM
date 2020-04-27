<?php


namespace App\Application\Familiar\DisplayList;


use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository;
use App\Domain\User\User;

class FamiliarListService
{
    private FamiliarRepository $repository;

    public function __construct(FamiliarRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Familiar[]
     */
    public function __invoke(User $user): array
    {
        if($user instanceof Customer){
            return $this->repository->findByCustomer($user);
        }

        return $this->repository->findAll();
    }
}