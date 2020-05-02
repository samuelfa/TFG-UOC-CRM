<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\View\FamiliarViewService;
use App\Application\Familiar\View\ViewFamiliarDTO;
use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use PHPUnit\Framework\TestCase;

class ViewFamiliarServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private FamiliarViewService $handler;
    private InMemoryCustomerRepository $customerRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->handler = new FamiliarViewService($this->repository);
    }

    public function testViewFamiliar(): void
    {
        $nif = '12345678Z';

        $customer = $this->createCustomer('14756785P', 'password', 'one.email@dot.com');
        $this->createFamiliar($nif, $customer);

        $oneFamiliar = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Familiar::class, $oneFamiliar);

        $dto = new ViewFamiliarDTO($nif);
        $familiar = $this->handler->__invoke($dto);
        $this->assertEquals($oneFamiliar, $familiar);

        $this->repository->remove($familiar);
    }

    public function testFamiliarNotFound(): void
    {
        $nif = '12345678Z';

        $oneFamiliar = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($oneFamiliar);

        $dto = new ViewFamiliarDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('It suppose to not find any familiar');
        } catch (FamiliarNotFound $exception){
        }
    }

    private function createFamiliar($nif, Customer $customer): void
    {
        $familiar = new Familiar(new NIF($nif), $customer);
        $this->repository->save($familiar);
    }

    private function createCustomer($nif, $password, $emailAddress): Customer
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->customerRepository->save($customer);
        return $customer;
    }
}