<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Customer\DisplayList\CustomerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateGetController extends WebController
{
    public function view(CustomerListService $service, Request $request): Response
    {
        $customers = $service->__invoke();
        if(empty($customers)){
            return $this->redirectWithError('Please, create some customer first', $request, 'crm_familiar_list');
        }

        return $this->render('pages/crm/familiar/create.html.twig', [
            'customers' => $customers
        ]);
    }
}
