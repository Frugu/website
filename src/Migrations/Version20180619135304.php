<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180619135304 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE t_user_character (id UUID NOT NULL, user_id UUID NOT NULL, character_id INT NOT NULL, character_name VARCHAR(255) NOT NULL, main BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_939A3DD0A76ED395 ON t_user_character (user_id)');
        $this->addSql('COMMENT ON COLUMN t_user_character.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN t_user_character.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE t_user_character ADD CONSTRAINT FK_939A3DD0A76ED395 FOREIGN KEY (user_id) REFERENCES t_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE t_user_character DROP CONSTRAINT FK_939A3DD0A76ED395');
        $this->addSql('DROP TABLE t_user_character');
    }
}
