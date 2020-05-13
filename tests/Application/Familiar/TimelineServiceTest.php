<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\View\TimelineService;
use App\Domain\Activity\Activity;
use App\Domain\Category\Category;
use App\Domain\Customer\Customer;
use App\Domain\Familiar\Action\Email;
use App\Domain\Familiar\Action\LinkActivity;
use App\Domain\Familiar\Action\Note;
use App\Domain\Familiar\Familiar;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryEmailRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryLinkActivityRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryNoteRepository;
use PHPUnit\Framework\TestCase;

class TimelineServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private InMemoryCustomerRepository $customerRepository;
    private TimelineService $handler;
    private InMemoryNoteRepository $noteRepository;
    private InMemoryEmailRepository $emailRepository;
    private InMemoryLinkActivityRepository $linkActivityRepository;
    private InMemoryActivityRepository $activityRepository;
    private InMemoryCategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->noteRepository = new InMemoryNoteRepository([]);
        $this->emailRepository = new InMemoryEmailRepository([]);
        $this->linkActivityRepository = new InMemoryLinkActivityRepository([]);
        $this->activityRepository = new InMemoryActivityRepository([]);
        $this->categoryRepository = new InMemoryCategoryRepository([]);

        $this->handler = new TimelineService([
            $this->noteRepository,
            $this->emailRepository,
            $this->linkActivityRepository
        ]);
    }

    public function testListFamiliarsFromACustomer(): void
    {
        $customer = $this->createCustomer('93829751D', 'password', 'other.email@dot2.com');
        $familiar = $this->createFamiliar(new NIF('30107132V'), $customer);

        $this->createNote('My first note', false, $familiar);
        $this->createNote('My second note', true, $familiar);
        $this->createNote('My third note', false, $familiar);
        $this->createNote('My fourth note', true, $familiar);
        $this->createNote('My fifth note', false, $familiar);

        $this->createEmail('My first email', 'first body from email', [new EmailAddress('samuel84fa@gmail.com')], $familiar);
        $this->createEmail('My second email', 'second body from email', [new EmailAddress('samuelfa@uoc.edu')], $familiar);
        $this->createEmail('My third email', 'third body from email', [new EmailAddress('samuel@gmail.com')], $familiar);
        $this->createEmail('My fourth email', 'fourth body from email', [new EmailAddress('samuelfa@gmail.com')], $familiar);

        $category = $this->createCategory('Indoor');
        $firstActivity = $this->createActivity('Parchis', new \DateTimeImmutable(), new \DateTimeImmutable(), $category);
        $secondActivity = $this->createActivity('Mus', new \DateTimeImmutable(), new \DateTimeImmutable(), $category);
        $thirdActivity = $this->createActivity('Chess', new \DateTimeImmutable(), new \DateTimeImmutable(), $category);

        $this->createLinkActivity($familiar, $firstActivity);
        $this->createLinkActivity($familiar, $secondActivity);
        $this->createLinkActivity($familiar, $thirdActivity);

        $wholeList = $this->noteRepository->findByFamiliar($familiar);
        $this->assertCount(5, $wholeList);

        $wholeList = $this->emailRepository->findByFamiliar($familiar);
        $this->assertCount(4, $wholeList);

        $wholeList = $this->linkActivityRepository->findByFamiliar($familiar);
        $this->assertCount(3, $wholeList);

        $wholeList = $this->handler->__invoke($familiar);
        $this->assertCount(12, $wholeList);
        $this->assertCount(5, array_filter($wholeList, fn($element) => $element instanceof Note));
        $this->assertCount(4, array_filter($wholeList, fn($element) => $element instanceof Email));
        $this->assertCount(3, array_filter($wholeList, fn($element) => $element instanceof LinkActivity));
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

    private function createNote(string $message, bool $private, Familiar $familiar): void
    {
        static $id = 0;
        $id++;
        $action = new Note($id, $message, $private, $familiar, new \DateTimeImmutable());
        $this->noteRepository->save($action);
    }

    /**
     * @param EmailAddress[] $recipients
     */
    private function createEmail(string $subject, string $body, array $recipients, Familiar $familiar): void
    {
        static $id = 0;
        $id++;
        $action = new Email($id, $subject, $body, $recipients, $familiar, new \DateTimeImmutable());
        $this->emailRepository->save($action);
    }

    private function createLinkActivity(Familiar $familiar, Activity $activity): void
    {
        static $id = 0;
        $id++;
        $action = new LinkActivity($id, $familiar, $activity, new \DateTimeImmutable());
        $this->linkActivityRepository->save($action);
    }

    private function createActivity(string $name, \DateTimeImmutable $startAt, \DateTimeImmutable $finishAt, Category $category): Activity
    {
        $activity = Activity::create($name, $startAt, $finishAt, $category);
        $this->activityRepository->save($activity);
        return $activity;
    }

    private function createCategory(string $name): Category
    {
        $category = Category::create($name);
        $this->categoryRepository->save($category);
        return $category;
    }
}