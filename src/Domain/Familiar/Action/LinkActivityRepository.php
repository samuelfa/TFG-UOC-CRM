<?php


namespace App\Domain\Familiar\Action;


interface LinkActivityRepository extends ActionRepository
{
    public function save(LinkActivity $linkActivity): void;
    public function remove(LinkActivity $linkLinkActivity): void;
}