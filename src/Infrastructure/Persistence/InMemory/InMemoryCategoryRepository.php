<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepository;

class InMemoryCategoryRepository implements CategoryRepository
{
    /** @var Category[] */
    private array $list;

    /**
     * @param Category[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->id()] = $element;
        }
    }

    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        return $this->list;
    }

    public function save(Category $category): void
    {
        $this->list[$category->id()] = $category;
    }

    public function remove(Category $category): void
    {
        unset($this->list[$category->id()]);
    }

    public function flush(): void
    {}

    public function findOneById(int $id): ?Category
    {
        return $this->list[$id] ?? null;
    }

    public function findOneByName(string $name): ?Category
    {
        foreach ($this->list as $element){
            if($element->name() !== $name){
                continue;
            }

            return $element;
        }

        return null;
    }

    public function total(): int
    {
        return count($this->list);
    }
}