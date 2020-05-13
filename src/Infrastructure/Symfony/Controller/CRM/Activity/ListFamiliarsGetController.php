<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\DisplayList\FamiliarListService;
use App\Application\Activity\View\ActivityViewService;
use App\Application\Activity\View\ViewActivityDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListFamiliarsGetController extends WebController
{
    public function view(int $id, ActivityViewService $viewService, FamiliarListService $service): Response
    {
        $dto = new ViewActivityDTO($id);
        $activity = $viewService->__invoke($dto);
        return $this->render('pages/crm/activity/list-familiars.html.twig', [
            'activity' => $activity,
            'familiars' => $service->__invoke($activity)
        ]);
    }
}
