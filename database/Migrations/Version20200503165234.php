<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200503165234 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tfg_example.link_activities (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT \'(DC2Type:nif)\', activity INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E27B6F108A34CA5E (familiar), INDEX IDX_E27B6F10AC74095A (activity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.notes (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT \'(DC2Type:nif)\', message LONGTEXT NOT NULL, private TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_11BA68C8A34CA5E (familiar), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tfg_example.emails (id INT AUTO_INCREMENT NOT NULL, familiar VARCHAR(255) NOT NULL COMMENT \'(DC2Type:nif)\', subject VARCHAR(150) NOT NULL, body LONGTEXT NOT NULL, recipients LONGTEXT NOT NULL COMMENT \'(DC2Type:array_email_address)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4C81E8528A34CA5E (familiar), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tfg_example.link_activities ADD CONSTRAINT FK_E27B6F108A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif)');
        $this->addSql('ALTER TABLE tfg_example.link_activities ADD CONSTRAINT FK_E27B6F10AC74095A FOREIGN KEY (activity) REFERENCES activities (id)');
        $this->addSql('ALTER TABLE tfg_example.notes ADD CONSTRAINT FK_11BA68C8A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif)');
        $this->addSql('ALTER TABLE tfg_example.emails ADD CONSTRAINT FK_4C81E8528A34CA5E FOREIGN KEY (familiar) REFERENCES familiars (nif)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tfg_example.link_activities');
        $this->addSql('DROP TABLE tfg_example.notes');
        $this->addSql('DROP TABLE tfg_example.emails');
    }
}
