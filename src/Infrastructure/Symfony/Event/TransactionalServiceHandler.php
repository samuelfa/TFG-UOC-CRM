<?php


namespace App\Infrastructure\Symfony\Event;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Repository;

class TransactionalServiceHandler
{
    private iterable $services;
    private iterable $repositories;

    /**
     * CommandHandler constructor.
     * @param TransactionalService[] $services
     * @param Repository[] $repositories
     */
    public function __construct(iterable $services, iterable $repositories)
    {
        $this->services = $services;
        $this->repositories = $repositories;
    }

    public function dispatch(DTO $dto): DTO
    {
        foreach($this->findService(get_class($dto)) as $service){
            $dto = $service($dto);
        }

        foreach ($this->repositories as $repository){
            $repository->flush();
        }

        return $dto;
    }

    /**
     * @param string $dtoName
     * @return TransactionalService|\Generator
     */
    private function findService(string $dtoName): \Generator
    {
        $found = false;
        foreach ($this->services as $service){
            if($service->subscribeTo() === $dtoName){
                yield $service;
                $found = true;
            }
        }

        if(!$found){
            throw new TransactionalServiceNotFound($dtoName);
        }
    }
}