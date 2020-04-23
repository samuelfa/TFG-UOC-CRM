<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer;

use App\Application\Customer\DisplayList\CustomerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(CustomerListService $service): Response
    {
        return $this->render('pages/crm/customer/list.html.twig', [
            'customers' => $service->__invoke()
        ]);
    }
}
