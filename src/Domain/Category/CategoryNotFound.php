<?php


namespace App\Domain\Category;


class CategoryNotFound extends \RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct("Category {$name} not found");
    }

}