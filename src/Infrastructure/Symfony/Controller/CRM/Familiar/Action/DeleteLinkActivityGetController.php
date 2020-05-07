<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar\Action;

use App\Application\Familiar\Action\LinkActivity\DeleteLinkActivityDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteLinkActivityGetController extends WebController
{
    public function view(string $nif, int $id): RedirectResponse
    {
        $command = new DeleteLinkActivityDTO($nif, $id);
        $this->dispatch($command);

        return $this->redirectWithMessage('Linked Activity deleted', 'crm_familiar_view', ['nif' => $nif]);
    }
}
