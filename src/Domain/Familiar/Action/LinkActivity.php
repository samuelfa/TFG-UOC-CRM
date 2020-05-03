<?php


namespace App\Domain\Familiar\Action;


use App\Domain\Activity\Activity;
use App\Domain\Familiar\Familiar;

class LinkActivity implements Action
{
    private ?int $id;
    private Familiar $familiar;
    private Activity $activity;
    private \DateTimeImmutable $createdAt;

    public function __construct(?int $id, Familiar $familiar, Activity $activity, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->familiar  = $familiar;
        $this->activity = $activity;
        $this->createdAt = $createdAt;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public static function create(Familiar $familiar, Activity $activity): self
    {
        return new self(null, $familiar, $activity, new \DateTimeImmutable());
    }

    public function familiar(): Familiar
    {
        return $this->familiar;
    }

    public function activity(): Activity
    {
        return $this->activity;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}