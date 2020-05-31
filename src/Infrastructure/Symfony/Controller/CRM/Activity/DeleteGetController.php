<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\Delete\DeleteActivityDTO;
use App\Domain\Activity\ActivityLinkedWithFamiliars;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteGetController extends WebController
{
    public function view(int $id, Request $request): RedirectResponse
    {
        $command = new DeleteActivityDTO($id);

        try {
            $this->dispatch($command);
        } catch (ActivityLinkedWithFamiliars $exception){
            return $this->redirectWithError('activity','Activity linked with some familiars', $request, 'crm_activity_list');
        }

        return $this->redirectWithMessage('Activity deleted', 'crm_activity_list');
    }
}
