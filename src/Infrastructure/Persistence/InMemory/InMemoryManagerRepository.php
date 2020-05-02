<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

class InMemoryManagerRepository implements ManagerRepository
{
    /** @var Manager[] */
    private array $list;

    /**
     * @param Manager[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->nif()->value()] = $element;
        }
    }

    public function findOneByNif(NIF $nif): ?Manager
    {
        return $this->list[$nif->value()] ?? null;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Manager
    {
        foreach ($this->list as $element){
            if(!$element->emailAddress()->equals($emailAddress)){
                continue;
            }

            return $element;
        }

        return null;
    }

    /**
     * @return Manager[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    public function save(Manager $manager): void
    {
        $this->list[$manager->nif()->value()] = $manager;
    }

    public function remove(Manager $manager): void
    {
        unset($this->list[$manager->nif()->value()]);
    }

    public function flush(): void
    {}
}