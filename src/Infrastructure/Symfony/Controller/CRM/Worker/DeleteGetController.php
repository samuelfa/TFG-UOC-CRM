<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Worker;

use App\Application\Worker\Delete\DeleteWorkerDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(string $nif): RedirectResponse
    {
        $command = new DeleteWorkerDTO($nif);
        $this->dispatch($command);

        return $this->redirectWithMessage('crm_worker_list', 'Worker deleted');
    }
}
