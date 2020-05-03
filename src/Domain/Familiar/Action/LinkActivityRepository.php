<?php


namespace App\Domain\Familiar\Action;

use App\Domain\Repository;

interface LinkActivityRepository extends Repository
{
    public function save(LinkActivity $linkActivity): void;
    public function remove(LinkActivity $linkActivity): void;
}