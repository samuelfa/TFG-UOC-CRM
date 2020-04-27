<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427225558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tfg_example.familiars ADD customer VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:nif)\'');
        $this->addSql('ALTER TABLE tfg_example.familiars ADD CONSTRAINT FK_9757873281398E09 FOREIGN KEY (customer) REFERENCES customers (nif)');
        $this->addSql('CREATE INDEX IDX_9757873281398E09 ON tfg_example.familiars (customer)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tfg_example.familiars DROP FOREIGN KEY FK_9757873281398E09');
        $this->addSql('DROP INDEX IDX_9757873281398E09 ON tfg_example.familiars');
        $this->addSql('ALTER TABLE tfg_example.familiars DROP customer');
    }
}
