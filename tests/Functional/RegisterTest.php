<?php


namespace Test\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends WebTestCase
{
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();

        $connection = $kernel->getContainer()
                                      ->get('doctrine.orm.crm_entity_manager')
                                      ->getConnection()
        ;

        $connection->exec('DROP DATABASE IF EXISTS tfg_testing');
        $connection->executeQuery('DELETE FROM tfg_default.companies where email = ?', ['manager@testing.com']);
    }

    public function testRegisterANewCompany(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->filter('button')->form();

        $form['namespace'] = 'testing';
        $form['name'] = 'Company name';
        $form['nif'] = '12345678Z';
        $form['email_address'] = 'manager@testing.com';
        $form['password'] = 'testing';

        $this->client->submit($form);

        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
    }
}