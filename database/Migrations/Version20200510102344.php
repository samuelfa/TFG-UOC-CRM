<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510102344 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tfg_example.forgot_password_emails (email_address VARCHAR(150) NOT NULL COMMENT \'(DC2Type:email_address)\', string VARCHAR(600) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_C58BD1F79EBEB2A9 (string), PRIMARY KEY(email_address)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.link_activities (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT \'(DC2Type:nif)\', activity INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E27B6F108A34CA5E (familiar), INDEX IDX_E27B6F10AC74095A (activity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.notes (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT \'(DC2Type:nif)\', message LONGTEXT NOT NULL, private TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_11BA68C8A34CA5E (familiar), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.activities (id INT AUTO_INCREMENT NOT NULL, category SMALLINT NOT NULL, name VARCHAR(50) NOT NULL, start_at DATE NOT NULL, finish_at DATE NOT NULL, INDEX IDX_B5F1AFE564C19C1 (category), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.employees (nif VARCHAR(10) NOT NULL COMMENT \'(DC2Type:nif)\', name VARCHAR(150) DEFAULT NULL, surname VARCHAR(150) DEFAULT NULL, birthday DATE DEFAULT NULL, portrait VARCHAR(300) DEFAULT NULL COMMENT \'(DC2Type:url)\', password VARCHAR(300) NOT NULL COMMENT \'(DC2Type:password)\', email VARCHAR(150) NOT NULL COMMENT \'(DC2Type:email_address)\', role SMALLINT NOT NULL, UNIQUE INDEX UNIQ_BA82C300E7927C74 (email), PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.customers (nif VARCHAR(10) NOT NULL COMMENT \'(DC2Type:nif)\', name VARCHAR(150) DEFAULT NULL, surname VARCHAR(150) DEFAULT NULL, birthday DATE DEFAULT NULL, portrait VARCHAR(300) DEFAULT NULL COMMENT \'(DC2Type:url)\', password VARCHAR(300) NOT NULL COMMENT \'(DC2Type:password)\', email VARCHAR(150) NOT NULL COMMENT \'(DC2Type:email_address)\', UNIQUE INDEX UNIQ_62534E21E7927C74 (email), PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.categories (id SMALLINT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_3AF346685E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.familiars (nif VARCHAR(50) NOT NULL COMMENT \'(DC2Type:nif)\', customer VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:nif)\', name VARCHAR(150) DEFAULT NULL, surname VARCHAR(150) DEFAULT NULL, birthday DATE DEFAULT NULL, portrait VARCHAR(300) DEFAULT NULL COMMENT \'(DC2Type:url)\', INDEX IDX_9757873281398E09 (customer), PRIMARY KEY(nif)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.emails (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT \'(DC2Type:nif)\', subject VARCHAR(150) NOT NULL, body LONGTEXT NOT NULL, recipients LONGTEXT NOT NULL COMMENT \'(DC2Type:array_email_address)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4C81E8528A34CA5E (familiar), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tfg_example.link_activities ADD CONSTRAINT FK_E27B6F108A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif)');
        $this->addSql('ALTER TABLE tfg_example.link_activities ADD CONSTRAINT FK_E27B6F10AC74095A FOREIGN KEY (activity) REFERENCES activities (id)');
        $this->addSql('ALTER TABLE tfg_example.notes ADD CONSTRAINT FK_11BA68C8A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif)');
        $this->addSql('ALTER TABLE tfg_example.activities ADD CONSTRAINT FK_B5F1AFE564C19C1 FOREIGN KEY (category) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE tfg_example.familiars ADD CONSTRAINT FK_9757873281398E09 FOREIGN KEY (customer) REFERENCES customers (nif)');
        $this->addSql('ALTER TABLE tfg_example.emails ADD CONSTRAINT FK_4C81E8528A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tfg_example.link_activities DROP FOREIGN KEY FK_E27B6F10AC74095A');
        $this->addSql('ALTER TABLE tfg_example.familiars DROP FOREIGN KEY FK_9757873281398E09');
        $this->addSql('ALTER TABLE tfg_example.activities DROP FOREIGN KEY FK_B5F1AFE564C19C1');
        $this->addSql('ALTER TABLE tfg_example.link_activities DROP FOREIGN KEY FK_E27B6F108A34CA5E');
        $this->addSql('ALTER TABLE tfg_example.notes DROP FOREIGN KEY FK_11BA68C8A34CA5E');
        $this->addSql('ALTER TABLE tfg_example.emails DROP FOREIGN KEY FK_4C81E8528A34CA5E');
        $this->addSql('DROP TABLE tfg_example.forgot_password_emails');
        $this->addSql('DROP TABLE tfg_example.link_activities');
        $this->addSql('DROP TABLE tfg_example.notes');
        $this->addSql('DROP TABLE tfg_example.activities');
        $this->addSql('DROP TABLE tfg_example.employees');
        $this->addSql('DROP TABLE tfg_example.customers');
        $this->addSql('DROP TABLE tfg_example.categories');
        $this->addSql('DROP TABLE tfg_example.familiars');
        $this->addSql('DROP TABLE tfg_example.emails');
    }
}
