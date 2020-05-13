<?php


namespace App\Application\Stats;


use App\Domain\Customer\Customer;
use App\Domain\Familiar\Action\EmailRepository;
use App\Domain\Familiar\Action\LinkActivityRepository;
use App\Domain\Familiar\Action\NoteRepository;

class CustomerStatsService
{
    private EmailRepository $emailRepository;
    private LinkActivityRepository $linkActivityRepository;
    private NoteRepository $noteRepository;

    public function __construct(
        EmailRepository $emailRepository,
        LinkActivityRepository $linkActivityRepository,
        NoteRepository $noteRepository
    )
    {
        $this->emailRepository = $emailRepository;
        $this->linkActivityRepository = $linkActivityRepository;
        $this->noteRepository = $noteRepository;
    }

    public function __invoke(Customer $customer): CustomerStatsDTO
    {
        $emails = 0;
        $linkActivities = 0;
        $notes = 0;

        foreach ($customer->familiars() as $familiar){
            $emails += $this->emailRepository->total($familiar);
            $linkActivities += $this->linkActivityRepository->total($familiar);
            $notes += $this->noteRepository->total($familiar);
        }

        return new CustomerStatsDTO($emails, $linkActivities, $notes);
    }
}