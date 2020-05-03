<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Manager;

use App\Application\Manager\Delete\DeleteManagerDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(string $nif): RedirectResponse
    {
        $command = new DeleteManagerDTO($nif);
        $this->dispatch($command);

        return $this->redirectWithMessage('Manager deleted', 'crm_manager_list');
    }
}
