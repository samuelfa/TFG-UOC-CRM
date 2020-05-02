<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

class InMemoryWorkerRepository implements WorkerRepository
{
    /** @var Worker[] */
    private array $list;

    /**
     * @param Worker[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->nif()->value()] = $element;
        }
    }

    public function findOneByNif(NIF $nif): ?Worker
    {
        return $this->list[$nif->value()] ?? null;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Worker
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
     * @return Worker[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    public function save(Worker $worker): void
    {
        $this->list[$worker->nif()->value()] = $worker;
    }

    public function remove(Worker $worker): void
    {
        unset($this->list[$worker->nif()->value()]);
    }

    public function flush(): void
    {}
}