<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Familiar\Action\Email;
use App\Domain\Familiar\Action\EmailRepository;
use App\Domain\Familiar\Familiar;

class InMemoryEmailRepository implements EmailRepository
{
    /** @var Email[] */
    private array $list;

    /**
     * @param Email[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->id()] = $element;
        }
    }

    public function save(Email $activity): void
    {
        $this->list[$activity->id()] = $activity;
    }

    public function remove(Email $activity): void
    {
        unset($this->list[$activity->id()]);
    }

    public function flush(): void
    {}

    public function findByFamiliar(Familiar $familiar): array
    {
        $list = [];
        foreach ($this->list as $element){
            if(!$element->familiar()->nif()->equals($familiar->nif())){
                continue;
            }

            $list[] = $element;
        }

        return $list;
    }
}