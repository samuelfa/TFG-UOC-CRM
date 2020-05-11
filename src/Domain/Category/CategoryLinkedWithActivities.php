<?php


namespace App\Domain\Category;


class CategoryLinkedWithActivities extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Category {$id} linked with activities");
    }

}