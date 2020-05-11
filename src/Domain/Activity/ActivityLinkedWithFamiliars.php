<?php


namespace App\Domain\Activity;


class ActivityLinkedWithFamiliars extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Activity {$id} linked with familiars");
    }

}