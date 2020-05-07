<?php


namespace App\Application\Familiar\Action\LinkActivity;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Familiar\Action\LinkActivityNotFound;
use App\Domain\Familiar\Action\LinkActivityRepository;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class LinkActivityDeleteService implements TransactionalService
{
    private FamiliarRepository $repository;
    private LinkActivityRepository $linkActivityRepository;

    public function __construct(FamiliarRepository $repository, LinkActivityRepository $linkActivityRepository)
    {
        $this->repository = $repository;
        $this->linkActivityRepository = $linkActivityRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteLinkActivityDTO $dto */
        $familiar = $this->repository->findOneByNif($dto->nif());
        if(!$familiar){
            throw new FamiliarNotFound($dto->nif());
        }

        $linkActivity = $this->linkActivityRepository->findOneById($dto->id());
        if(!$linkActivity){
            throw new LinkActivityNotFound($dto->id());
        }

        $this->linkActivityRepository->remove($linkActivity);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteLinkActivityDTO::class;
    }
}