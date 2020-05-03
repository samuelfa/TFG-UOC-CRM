<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar\Action;

use App\Application\Activity\DisplayList\ActivityListService;
use App\Application\Familiar\View\FamiliarViewService;
use App\Application\Familiar\View\ViewFamiliarDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class LinkActivityGetController extends WebController
{
    public function view(
        string $nif,
        FamiliarViewService $service,
        ActivityListService $activityListService
    ): Response
    {
        $dto = new ViewFamiliarDTO($nif);
        return $this->render('pages/crm/familiar/action/link-activity.html.twig', [
            'familiar' => $service->__invoke($dto),
            'activities' => $activityListService->__invoke()
        ]);
    }
}
