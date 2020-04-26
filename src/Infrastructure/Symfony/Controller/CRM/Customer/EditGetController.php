<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer;

use App\Application\Customer\View\CustomerViewService;
use App\Application\Customer\View\ViewCustomerDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class EditGetController extends WebController
{
    public function view(string $nif, CustomerViewService $service): Response
    {
        $dto = new ViewCustomerDTO($nif);
        return $this->render('pages/crm/customer/edit.html.twig', ['customer' => $service->__invoke($dto)]);
    }
}
