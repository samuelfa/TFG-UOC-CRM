<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

class InMemoryCustomerRepository implements CustomerRepository
{
    /** @var Customer[] */
    private array $list;

    /**
     * @param Customer[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->nif()->value()] = $element;
        }
    }

    public function findOneByNif(NIF $nif): ?Customer
    {
        return $this->list[$nif->value()] ?? null;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Customer
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
     * @return Customer[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    public function save(Customer $customer): void
    {
        $this->list[$customer->nif()->value()] = $customer;
    }

    public function remove(Customer $customer): void
    {
        unset($this->list[$customer->nif()->value()]);
    }

    public function flush(): void
    {}

    public function total(): int
    {
        return count($this->list);
    }
}