<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\Delete\DeleteActivityDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(int $id): RedirectResponse
    {
        $command = new DeleteActivityDTO($id);
        $this->dispatch($command);

        return $this->redirectWithMessage('Activity deleted', 'crm_activity_list');
    }
}
