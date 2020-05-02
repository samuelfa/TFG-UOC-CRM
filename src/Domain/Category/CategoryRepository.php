<?php

namespace App\Domain\Category;

use App\Domain\Repository;

interface CategoryRepository extends Repository
{
    public function findOneByName(string $name): ?Category;
    /**
     * @return Category[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Category $category): void;
    public function remove(Category $category): void;
}
