<?php


namespace App\Domain\Familiar\Action;


use App\Domain\Activity\Activity;
use App\Domain\Familiar\Familiar;

interface LinkActivityRepository extends ActionRepository
{
    public function save(LinkActivity $linkActivity): void;
    public function remove(LinkActivity $linkLinkActivity): void;
    public function findOneById(int $id): ?LinkActivity;
    public function findByFamiliarAndDates(Familiar $familiar, \DateTime $start, \DateTime $end): array;
    public function findByActivity(Activity $activity): array;
}