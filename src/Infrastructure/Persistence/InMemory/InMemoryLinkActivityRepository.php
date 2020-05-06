<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Familiar\Action\LinkActivity;
use App\Domain\Familiar\Action\LinkActivityRepository;
use App\Domain\Familiar\Familiar;

class InMemoryLinkActivityRepository implements LinkActivityRepository
{
    /** @var LinkActivity[] */
    private array $list;

    /**
     * @param LinkActivity[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->id()] = $element;
        }
    }

    public function save(LinkActivity $activity): void
    {
        $this->list[$activity->id()] = $activity;
    }

    public function remove(LinkActivity $linkActivity): void
    {
        unset($this->list[$linkActivity->id()]);
    }

    public function flush(): void
    {}

    /**
     * @return LinkActivity[]
     */
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