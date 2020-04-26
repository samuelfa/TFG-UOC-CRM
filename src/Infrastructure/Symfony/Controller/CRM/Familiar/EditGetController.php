<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\View\FamiliarViewService;
use App\Application\Familiar\View\ViewFamiliarDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class EditGetController extends WebController
{
    public function view(string $nif, FamiliarViewService $service): Response
    {
        $dto = new ViewFamiliarDTO($nif);
        return $this->render('pages/crm/familiar/edit.html.twig', ['familiar' => $service->__invoke($dto)]);
    }
}
