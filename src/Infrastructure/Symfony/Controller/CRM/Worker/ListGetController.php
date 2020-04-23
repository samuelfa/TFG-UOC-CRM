<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Worker;

use App\Application\Worker\DisplayList\WorkerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(WorkerListService $service): Response
    {
        return $this->render('pages/crm/worker/list.html.twig', [
            'workers' => $service->__invoke()
        ]);
    }
}
