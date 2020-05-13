<?php


namespace App\Application\Stats;


use App\Domain\Activity\ActivityRepository;
use App\Domain\Category\CategoryRepository;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Employee\Employee;
use App\Domain\Familiar\FamiliarRepository;

class EmployeeStatsService
{
    private CategoryRepository $categoryRepository;
    private ActivityRepository $activityRepository;
    private CustomerRepository $customerRepository;
    private FamiliarRepository $familiarRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ActivityRepository $activityRepository,
        CustomerRepository $customerRepository,
        FamiliarRepository $familiarRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->activityRepository = $activityRepository;
        $this->customerRepository = $customerRepository;
        $this->familiarRepository = $familiarRepository;
    }

    public function __invoke(Employee $employee): EmployeeStatsDTO
    {
       return new EmployeeStatsDTO(
            $this->categoryRepository->total(),
            $this->activityRepository->total(),
            $this->customerRepository->total(),
            $this->familiarRepository->total()
        );
    }
}