<?php

namespace App\Infrastructure\Symfony\Controller\CRM;

use App\Application\Stats\CustomerStatsService;
use App\Application\Stats\EmployeeStatsService;
use App\Domain\Customer\Customer;
use App\Domain\Employee\Employee;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends WebController
{
    public function view(CustomerStatsService $customerStatsService, EmployeeStatsService $employeeStatsService): Response
    {
        $user = $this->getUser();
        if($user instanceof Employee){
            return $this->render('pages/crm/dashboard_employee.html.twig', [
                'stats' => $employeeStatsService->__invoke($user)
            ]);
        }

        /** @var Customer $user */
        return $this->render('pages/crm/dashboard_customer.html.twig', [
            'stats' => $customerStatsService->__invoke($user)
        ]);
    }
}
