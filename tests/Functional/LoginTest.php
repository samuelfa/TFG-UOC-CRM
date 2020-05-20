<?php


namespace Test\Functional;


use App\Domain\Company\CloneCustomerRepository;
use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository;
use App\Domain\Employee\Manager;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['HTTP_HOST' => 'crm.localhost']);
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $em = $container->get('doctrine.orm.crm_entity_manager');
        $connection = $em->getConnection();

        $connection->exec('DROP DATABASE IF EXISTS tfg_functional');
        $connection->executeQuery(/** @lang GenericSQL */ 'DELETE FROM tfg_default.companies where email = ?', ['manager@functional.com']);

        $namespace = 'functional';
        $nif = new NIF('12345678Z');
        $emailAddress = new EmailAddress('manager@functional.com');
        $password = Password::encode('functional');
        $manager = Manager::create($nif, $emailAddress, $password);

        $company = Company::create($namespace, 'Functional company', $emailAddress);
        $repository = $container->get(CompanyRepository::class);
        $repository->save($company);

        $repository = $container->get(CloneCustomerRepository::class);
        $repository->create('functional', $manager);
    }
    
    public function testRegisterANewCompany(): void
    {
        $crawler = $this->client->request('GET', '/signin');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->filter('button')->form();

        $form['namespace'] = 'functional';

        $this->client->submit($form);

        /** @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertEquals('http://functional.crm.localhost/login', $response->getTargetUrl());

        $crawler = $this->client->followRedirect();

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('http://functional.crm.localhost/login', $this->client->getHistory()->current()->getUri());

        $form = $crawler->filter('button')->form();

        $form['email_address'] = 'manager@functional.com';
        $form['password'] = 'functional';

        $this->client->submit($form);

        /** @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertEquals('/crm', $response->getTargetUrl());

        $crawler = $this->client->followRedirect();

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('http://functional.crm.localhost/crm', $this->client->getHistory()->current()->getUri());

        $total = $crawler->filter('div.card-body button.btn-primary')->count();
        $this->assertEquals(4, $total);
    }
}