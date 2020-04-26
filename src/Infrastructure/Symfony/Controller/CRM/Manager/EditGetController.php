<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Manager;

use App\Application\Manager\View\ManagerViewService;
use App\Application\Manager\View\ViewManagerDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class EditGetController extends WebController
{
    public function view(string $nif, ManagerViewService $service): Response
    {
        $dto = new ViewManagerDTO($nif);
        return $this->render('pages/crm/manager/edit.html.twig', ['manager' => $service->__invoke($dto)]);
    }
}
