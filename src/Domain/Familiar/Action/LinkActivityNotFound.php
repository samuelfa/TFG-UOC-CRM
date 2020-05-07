<?php


namespace App\Domain\Familiar\Action;


class LinkActivityNotFound extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Link Activity {$id} not found");
    }

}