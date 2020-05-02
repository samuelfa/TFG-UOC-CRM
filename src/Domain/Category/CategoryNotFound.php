<?php


namespace App\Domain\Category;


class CategoryNotFound extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Category {$id} not found");
    }

}