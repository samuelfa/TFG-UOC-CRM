<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer;

use App\Application\Customer\Delete\DeleteCustomerDTO;
use App\Domain\Customer\CustomerLinkedWithFamiliars;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteGetController extends WebController
{
    public function view(string $nif, Request $request): RedirectResponse
    {
        $command = new DeleteCustomerDTO($nif);

        try {
            $this->dispatch($command);
        } catch (CustomerLinkedWithFamiliars $exception){
            return $this->redirectWithError('Customer linked with a familiar', $request, 'crm_customer_list');
        }

        return $this->redirectWithMessage('Customer deleted', 'crm_customer_list');
    }
}
