<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Manager;

use App\Application\Manager\DisplayList\ManagerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(ManagerListService $service): Response
    {
        return $this->render('pages/crm/manager/list.html.twig', [
            'managers' => $service->__invoke()
        ]);
    }
}
