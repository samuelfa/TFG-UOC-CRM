<?php


namespace App\Application\Stats;


class CustomerStatsDTO
{
    private int $emails;
    private int $linkActivities;
    private int $notes;

    public function __construct(int $emails, int $linkActivities, int $notes)
    {
        $this->emails = $emails;
        $this->linkActivities = $linkActivities;
        $this->notes = $notes;
    }

    public function emails(): int
    {
        return $this->emails;
    }

    public function linkActivities(): int
    {
        return $this->linkActivities;
    }

    public function notes(): int
    {
        return $this->notes;
    }
}