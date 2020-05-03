<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer;

use App\Application\Customer\Delete\DeleteCustomerDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(string $nif): RedirectResponse
    {
        $command = new DeleteCustomerDTO($nif);
        $this->dispatch($command);

        return $this->redirectWithMessage('Customer deleted', 'crm_customer_list');
    }
}
