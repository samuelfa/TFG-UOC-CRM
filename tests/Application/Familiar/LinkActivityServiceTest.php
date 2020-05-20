<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\Action\LinkActivity\LinkActivityDTO;
use App\Application\Familiar\Action\LinkActivity\LinkActivityService;
use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Category\Category;
use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryLinkActivityRepository;
use PHPUnit\Framework\TestCase;

class LinkActivityServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private InMemoryCustomerRepository $customerRepository;
    private InMemoryActivityRepository $activityRepository;
    private InMemoryLinkActivityRepository $linkActivityRepository;
    private LinkActivityService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->activityRepository = new InMemoryActivityRepository([]);
        $this->linkActivityRepository = new InMemoryLinkActivityRepository([]);
        $this->handler = new LinkActivityService($this->repository, $this->activityRepository, $this->linkActivityRepository);
    }

    public function testLinkActivity(): void
    {
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $familiar = $this->createFamiliar('12345678Z', $customer);
        $activity = $this->createActivity(1, 'Yoga');

        $list = $this->linkActivityRepository->findByFamiliar($familiar);

        $this->assertCount(0, $list);

        $dto = new LinkActivityDTO($familiar->nif(), $activity->id());

        $this->handler->__invoke($dto);

        $list = $this->linkActivityRepository->findByFamiliar($familiar);

        $this->assertCount(1, $list);

        [$action] = $list;

        $this->assertEquals($familiar, $action->familiar());
        $this->assertEquals($activity, $action->activity());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($customer);
        $this->activityRepository->remove($activity);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

    public function testFailWhenFamiliarNotFound(): void
    {
        $familiar = $this->repository->findOneByNif(new NIF('12345678Z'));
        $this->assertNull($familiar);

        $dto = new LinkActivityDTO('12345678Z', 1);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The familiar not exists!');
        } catch (FamiliarNotFound $exception){
            $this->assertTrue(true);
        }
    }

    public function testFailWhenActivityNotFound(): void
    {
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $familiar = $this->createFamiliar('12345678Z', $customer);

        $activity = $this->activityRepository->findOneById(1);
        $this->assertNull($activity);

        $dto = new LinkActivityDTO($familiar->nif(), 1);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The activity not exists!');
        } catch (ActivityNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function createFamiliar($nif, Customer $customer): Familiar
    {
        $familiar = new Familiar(new NIF($nif), $customer);
        $this->repository->save($familiar);
        return $familiar;
    }

    private function createCustomer($nif, $password, $emailAddress): Customer
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->customerRepository->save($customer);
        return $customer;
    }

    private function createActivity(int $id, string $name): Activity
    {
        $activity = new Activity($id, $name, new \DateTimeImmutable(), new \DateTimeImmutable(), new Category(1, 'Indoor'));
        $this->activityRepository->save($activity);
        return $activity;
    }
}