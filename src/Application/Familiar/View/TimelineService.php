<?php


namespace App\Application\Familiar\View;


use App\Domain\Familiar\Action\Action;
use App\Domain\Familiar\Action\ActionRepository;
use App\Domain\Familiar\Familiar;

class TimelineService
{
    /**
     * @var ActionRepository[]
     */
    private iterable $repositories;

    /**
     * @param ActionRepository[] $repositories
     */
    public function __construct(iterable $repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * @param Familiar $familiar
     * @return Action[]
     */
    public function __invoke(Familiar $familiar): array
    {
        $timeline = [];
        foreach ($this->repositories as $repository){
            $timeline = [...$repository->findByFamiliar($familiar), ...$timeline];
        }

        usort($timeline, static function(Action $left, Action $right){
            return $right->createdAt() <=> $left->createdAt();
        });

        return $timeline;
    }
}