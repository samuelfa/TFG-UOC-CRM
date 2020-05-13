<?php


namespace App\Domain\Familiar\Action;


use App\Domain\Activity\Activity;

interface LinkActivityRepository extends ActionRepository
{
    public function save(LinkActivity $linkActivity): void;
    public function remove(LinkActivity $linkLinkActivity): void;
    public function findOneById(int $id): ?LinkActivity;
    /** @return LinkActivity[] */
    public function findByActivity(Activity $activity): array;
}