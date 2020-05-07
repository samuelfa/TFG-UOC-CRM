<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar\Action;

use App\Application\Familiar\View\FamiliarViewService;
use App\Application\Familiar\View\ViewFamiliarDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class CalendarGetController extends WebController
{
    public function view(
        string $nif,
        FamiliarViewService $service
    ): Response
    {
        $dto = new ViewFamiliarDTO($nif);
        return $this->render('pages/crm/familiar/action/calendar.html.twig', [
            'familiar' => $service->__invoke($dto),
            'user' => $this->getUser()
        ]);
    }
}
