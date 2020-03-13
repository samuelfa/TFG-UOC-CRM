<?php


namespace App\Infrastructure\Symfony\Factory;


use App\Domain\Company\CompanyNotFound;
use App\Domain\Company\CompanyRepository;
use App\Domain\Factory\ConnectionFactory as ConnectionFactoryInterface;

class ConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * @var CompanyRepository
     */
    private CompanyRepository $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function preloadSettings(string $namespace): void
    {
        $company = $this->repository->findOneByNamespace($namespace);
        if(!$company){
            throw new CompanyNotFound($namespace);
        }

        $_ENV['CUSTOMER_DATABASE_URL'] = str_replace('default', $namespace, $_ENV['DEFAULT_DATABASE_URL']);
    }
}