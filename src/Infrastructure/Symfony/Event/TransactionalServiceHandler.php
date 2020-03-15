<?php


namespace App\Infrastructure\Symfony\Event;


use App\Application\DTO;
use App\Application\TransactionalService;
use Doctrine\ORM\EntityManagerInterface;

class TransactionalServiceHandler
{
    private iterable $services;
    private EntityManagerInterface $entityManager;

    /**
     * CommandHandler constructor.
     * @param TransactionalService[] $services
     */
    public function __construct(iterable $services, EntityManagerInterface $entityManager)
    {
        $this->services = $services;
        $this->entityManager = $entityManager;
    }

    public function dispatch(DTO $dto): DTO
    {
        foreach($this->findService(get_class($dto)) as $service){
            $dto = $service($dto);
        }
        $this->entityManager->flush();

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