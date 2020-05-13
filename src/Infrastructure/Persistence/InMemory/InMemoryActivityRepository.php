<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Category\Category;

class InMemoryActivityRepository implements ActivityRepository
{
    /** @var Activity[] */
    private array $list;

    /**
     * @param Activity[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->id()] = $element;
        }
    }

    /**
     * @return Activity[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    public function save(Activity $activity): void
    {
        $this->list[$activity->id()] = $activity;
    }

    public function remove(Activity $activity): void
    {
        unset($this->list[$activity->id()]);
    }

    public function flush(): void
    {}

    public function findOneById(int $id): ?Activity
    {
        return $this->list[$id] ?? null;
    }

    public function findByCategory(Category $category): array
    {
        $list = [];
        foreach ($this->list as $activity){
            if($activity->category() !== $category){
                continue;
            }
            $list[] = $activity;
        }

        return $list;
    }

    public function total(): int
    {
        return count($this->list);
    }
}