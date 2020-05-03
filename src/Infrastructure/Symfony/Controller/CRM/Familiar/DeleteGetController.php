<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\Delete\DeleteFamiliarDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(string $nif): RedirectResponse
    {
        $command = new DeleteFamiliarDTO($nif);
        $this->dispatch($command);

        return $this->redirectWithMessage('Familiar deleted', 'crm_familiar_list');
    }
}
