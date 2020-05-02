<?php


namespace App\Domain\Activity;


class ActivityNotFound extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Activity {$id} not found");
    }

}