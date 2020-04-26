<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Worker;

use App\Application\Worker\View\WorkerViewService;
use App\Application\Worker\View\ViewWorkerDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class EditGetController extends WebController
{
    public function view(string $nif, WorkerViewService $service): Response
    {
        $dto = new ViewWorkerDTO($nif);
        return $this->render('pages/crm/worker/edit.html.twig', ['worker' => $service->__invoke($dto)]);
    }
}
