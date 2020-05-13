<?php


namespace App\Application\Stats;


class EmployeeStatsDTO
{
    private int $categories;
    private int $activities;
    private int $customers;
    private int $familiars;

    public function __construct(int $categories, int $activities, int $customers, int $familiars)
    {
        $this->categories = $categories;
        $this->activities = $activities;
        $this->customers = $customers;
        $this->familiars = $familiars;
    }

    public function categories(): int
    {
        return $this->categories;
    }

    public function activities(): int
    {
        return $this->activities;
    }

    public function customers(): int
    {
        return $this->customers;
    }

    public function familiars(): int
    {
        return $this->familiars;
    }
}