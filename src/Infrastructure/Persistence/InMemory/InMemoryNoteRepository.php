<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Familiar\Action\Note;
use App\Domain\Familiar\Action\NoteRepository;
use App\Domain\Familiar\Familiar;

class InMemoryNoteRepository implements NoteRepository
{
    /** @var Note[] */
    private array $list;

    /**
     * @param Note[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->id()] = $element;
        }
    }

    public function save(Note $note): void
    {
        $this->list[$note->id()] = $note;
    }

    public function remove(Note $note): void
    {
        unset($this->list[$note->id()]);
    }

    public function flush(): void
    {}

    /**
     * @param Familiar $familiar
     * @return Note[]
     */
    public function findByFamiliar(Familiar $familiar): array
    {
        $list = [];
        foreach ($this->list as $element){
            if(!$element->familiar()->nif()->equals($familiar->nif())){
                continue;
            }

            $list[] = $element;
        }

        return $list;
    }
}