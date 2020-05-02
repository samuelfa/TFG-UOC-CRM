<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\DisplayList\FamiliarListService;
use App\Domain\Customer\Customer;
use App\Domain\Employee\Manager;
use App\Domain\Employee\Worker;
use App\Domain\Familiar\Familiar;
use App\Domain\User\User;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use PHPUnit\Framework\TestCase;

class ListFamiliarsServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private InMemoryCustomerRepository $customerRepository;
    private FamiliarListService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->handler = new FamiliarListService($this->repository);
    }

    public function testListFamiliarsFromACustomer(): void
    {
        $customer = $this->createCustomer('14756785P', 'password', 'one.email@dot.com');
        $nifValues = ['12345678Z', '61011902D', '39115577Y', 'Z0362318G'];
        foreach ($nifValues as $value){
            $this->createFamiliar(new NIF($value), $customer);
        }

        $otherCustomer = $this->createCustomer('93829751D', 'password', 'other.email@dot2.com');
        $otherNifValues = ['30107132V', 'Z0275626E', '35475195H', 'Y1575131J'];
        foreach ($otherNifValues as $value){
            $this->createFamiliar(new NIF($value), $otherCustomer);
        }

        $wholeList = $this->repository->findAll();
        $this->assertCount(8, $wholeList);

        $this->validateListForUser($customer, $nifValues, $otherNifValues);
        $this->validateListForUser($otherCustomer, $otherNifValues, $nifValues);

        foreach ($wholeList as $familiar){
            $this->repository->remove($familiar);
        }
    }

    public function testListFamiliarsFromAManager(): void
    {
        $customer = $this->createCustomer('14756785P', 'password', 'one.email@dot.com');
        $nifValues = ['12345678Z', '61011902D', '39115577Y', 'Z0362318G'];
        foreach ($nifValues as $value){
            $this->createFamiliar(new NIF($value), $customer);
        }

        $otherCustomer = $this->createCustomer('93829751D', 'password', 'other.email@dot2.com');
        $otherNifValues = ['30107132V', 'Z0275626E', '35475195H', 'Y1575131J'];
        foreach ($otherNifValues as $value){
            $this->createFamiliar(new NIF($value), $otherCustomer);
        }

        $wholeList = $this->repository->findAll();
        $this->assertCount(8, $wholeList);

        $allValues = array_merge($nifValues, $otherNifValues);

        $manager = Manager::create(new NIF('22980354L'), new EmailAddress('one.email@dot3.com'), Password::encode('password'));
        $this->validateListForUser($manager, $allValues, []);

        foreach ($wholeList as $familiar){
            $this->repository->remove($familiar);
        }
    }

    public function testListFamiliarsFromAWorker(): void
    {
        $customer = $this->createCustomer('14756785P', 'password', 'one.email@dot.com');
        $nifValues = ['12345678Z', '61011902D', '39115577Y', 'Z0362318G'];
        foreach ($nifValues as $value){
            $this->createFamiliar(new NIF($value), $customer);
        }

        $otherCustomer = $this->createCustomer('93829751D', 'password', 'other.email@dot2.com');
        $otherNifValues = ['30107132V', 'Z0275626E', '35475195H', 'Y1575131J'];
        foreach ($otherNifValues as $value){
            $this->createFamiliar(new NIF($value), $otherCustomer);
        }

        $wholeList = $this->repository->findAll();
        $this->assertCount(8, $wholeList);

        $allValues = array_merge($nifValues, $otherNifValues);

        $manager = Worker::create(new NIF('22980354L'), new EmailAddress('one.email@dot3.com'), Password::encode('password'));
        $this->validateListForUser($manager, $allValues, []);

        foreach ($wholeList as $familiar){
            $this->repository->remove($familiar);
        }
    }

    private function validateListForUser(User $customer, array $nifValues, array $otherNifValues): void
    {
        $givenList = $this->handler->__invoke($customer);
        $this->assertCount(count($nifValues), $givenList);

        $counter = 0;
        foreach ($givenList as $familiar){
            $this->assertInstanceOf(Familiar::class, $familiar);
            $this->assertEquals($nifValues[$counter], $familiar->nif()->value());
            $counter++;
        }

        if(!empty($otherNifValues)){
            $counter = 0;
            foreach ($givenList as $familiar){
                $this->assertInstanceOf(Familiar::class, $familiar);
                $this->assertNotEquals($otherNifValues[$counter], $familiar->nif()->value());
                $counter++;
            }
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