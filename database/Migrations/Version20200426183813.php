<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426183813 extends AbstractMigration
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

        $this->addSql('ALTER TABLE tfg_example.employees RENAME INDEX uniq_c3183a4de7927c74 TO UNIQ_BA82C300E7927C74');
        $this->addSql('ALTER TABLE tfg_example.customers RENAME INDEX uniq_1bc9b76ce7927c74 TO UNIQ_62534E21E7927C74');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        /** @noinspection NullPointerExceptionInspection */
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tfg_example.customers RENAME INDEX uniq_62534e21e7927c74 TO UNIQ_1BC9B76CE7927C74');
        $this->addSql('ALTER TABLE tfg_example.employees RENAME INDEX uniq_ba82c300e7927c74 TO UNIQ_C3183A4DE7927C74');
    }
}
