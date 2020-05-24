<?php


namespace App\Application;


use App\Domain\Activity\Activity;

class CalendarTransform
{
    /**
     * @param Activity[] $activities
     * @return array[]
     */
    public function toArray(array $activities): array
    {
        $list = [];
        foreach ($activities as $activity){
            $list[] = [
                'id' => $activity->id(),
                'title' => $activity->name(),
                'start' => $activity->startAt()->format(\DateTime::ATOM),
                'end' => $activity->finishAt()->format(\DateTime::ATOM)
            ];
        }

        return $list;
    }
}