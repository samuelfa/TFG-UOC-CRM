<?php


namespace App\Domain\Familiar;


class FamiliarNotFound extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("Familiar {$nif} not found");
    }

}