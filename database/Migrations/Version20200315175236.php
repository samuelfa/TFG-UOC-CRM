<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315175236 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tfg_example.familiars (nif VARCHAR(255) NOT NULL, name VARCHAR(150) NOT NULL, surname VARCHAR(150) NOT NULL, birthday DATE NOT NULL, portrait VARCHAR(300) NOT NULL, PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.users (nif VARCHAR(255) NOT NULL, name VARCHAR(150) NOT NULL, surname VARCHAR(150) NOT NULL, birthday DATE NOT NULL, portrait VARCHAR(300) NOT NULL, password VARCHAR(150) NOT NULL, email VARCHAR(150) NOT NULL, role SMALLINT UNSIGNED NOT NULL, PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tfg_example.familiars');
        $this->addSql('DROP TABLE tfg_example.users');
    }
}
