<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200412165618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tfg_example.employees (nif VARCHAR(10) NOT NULL COMMENT \'(DC2Type:nif)\', name VARCHAR(150) DEFAULT NULL, surname VARCHAR(150) DEFAULT NULL, birthday DATE DEFAULT NULL, portrait VARCHAR(300) DEFAULT NULL COMMENT \'(DC2Type:url)\', password VARCHAR(300) NOT NULL COMMENT \'(DC2Type:password)\', email VARCHAR(150) NOT NULL COMMENT \'(DC2Type:email_address)\', role SMALLINT NOT NULL COMMENT \'(DC2Type:role)\', UNIQUE INDEX UNIQ_C3183A4DE7927C74 (email), PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.customers (nif VARCHAR(10) NOT NULL COMMENT \'(DC2Type:nif)\', name VARCHAR(150) DEFAULT NULL, surname VARCHAR(150) DEFAULT NULL, birthday DATE DEFAULT NULL, portrait VARCHAR(300) DEFAULT NULL COMMENT \'(DC2Type:url)\', password VARCHAR(300) NOT NULL COMMENT \'(DC2Type:password)\', email VARCHAR(150) NOT NULL COMMENT \'(DC2Type:email_address)\', UNIQUE INDEX UNIQ_1BC9B76CE7927C74 (email), PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.familiars (nif VARCHAR(50) NOT NULL, name VARCHAR(150) NOT NULL, surname VARCHAR(150) NOT NULL, birthday DATE NOT NULL, portrait VARCHAR(300) NOT NULL COMMENT \'(DC2Type:url)\', PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tfg_example.employees');
        $this->addSql('DROP TABLE tfg_example.customers');
        $this->addSql('DROP TABLE tfg_example.familiars');
    }
}
