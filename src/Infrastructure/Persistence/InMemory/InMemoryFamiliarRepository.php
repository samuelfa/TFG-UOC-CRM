<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository;
use App\Domain\ValueObject\NIF;

class InMemoryFamiliarRepository implements FamiliarRepository
{
    /** @var Familiar[] */
    private array $list;

    /**
     * @param Familiar[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->nif()->value()] = $element;
        }
    }

    public function findOneByNif(NIF $nif): ?Familiar
    {
        return $this->list[$nif->value()] ?? null;
    }

    /**
     * @return Familiar[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    public function save(Familiar $familiar): void
    {
        $this->list[$familiar->nif()->value()] = $familiar;
    }

    public function remove(Familiar $familiar): void
    {
        unset($this->list[$familiar->nif()->value()]);
    }

    public function flush(): void
    {}

    /**
     * @return Familiar[]
     */
    public function findByCustomer(Customer $customer): array
    {
        $list = [];
        foreach ($this->list as $element){
            if(!$element->customer()->nif()->equals($customer->nif())){
                continue;
            }

            $list[] = $element;
        }

        return $list;
    }
}