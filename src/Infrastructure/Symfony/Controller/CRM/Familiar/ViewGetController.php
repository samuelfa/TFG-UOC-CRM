<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\View\FamiliarViewService;
use App\Application\Familiar\View\TimelineService;
use App\Application\Familiar\View\ViewFamiliarDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ViewGetController extends WebController
{
    public function view(string $nif, FamiliarViewService $service, TimelineService $timelineService): Response
    {
        $dto = new ViewFamiliarDTO($nif);
        $familiar = $service->__invoke($dto);
        $timeline = $timelineService->__invoke($familiar);
        return $this->render('pages/crm/familiar/view.html.twig', [
            'familiar' => $familiar,
            'timeline' => $timeline
        ]);
    }
}
