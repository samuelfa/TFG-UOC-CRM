<?php

namespace App\Domain\Activity;

use App\Domain\Category\Category;
use App\Domain\Repository;

interface ActivityRepository extends Repository
{
    public function findOneById(int $id): ?Activity;
    public function findByCategory(Category $category): array;
    /**
     * @return Activity[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Activity $activity): void;
    public function remove(Activity $activity): void;
    public function total(): int;
}
