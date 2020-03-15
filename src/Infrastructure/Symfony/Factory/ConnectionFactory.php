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
        if(empty($namespace)){
            return;
        }

        $company = $this->repository->findOneByNamespace($namespace);
        if(!$company){
            throw new CompanyNotFound($namespace);
        }

        $_ENV['CRM_DATABASE_URL'] = str_replace('[customer]', $namespace, $_ENV['CRM_TEMPLATE_DATABASE_URL']);
    }
}