<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\DisplayList\ActivityListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(ActivityListService $service): Response
    {
        return $this->render('pages/crm/activity/list.html.twig', [
            'activities' => $service->__invoke()
        ]);
    }
}
