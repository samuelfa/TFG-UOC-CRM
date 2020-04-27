<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Customer\DisplayList\CustomerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class CreateGetController extends WebController
{
    public function view(CustomerListService $service): Response
    {
        $customers = $service->__invoke();

        return $this->render('pages/crm/familiar/create.html.twig', [
            'customers' => $customers
        ]);
    }
}
