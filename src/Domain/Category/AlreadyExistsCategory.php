<?php


namespace App\Domain\Category;


class AlreadyExistsCategory extends \RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('The category name %s is already in use', $name));
    }

}