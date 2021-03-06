<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180714192224 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE t_conversation (id UUID NOT NULL, category_id UUID NOT NULL, author_id UUID NOT NULL, parent_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E912469DE2 ON t_conversation (category_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9F675F31B ON t_conversation (author_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9727ACA70 ON t_conversation (parent_id)');
        $this->addSql('COMMENT ON COLUMN t_conversation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN t_conversation.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN t_conversation.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN t_conversation.parent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE t_conversation ADD CONSTRAINT FK_8A8E26E912469DE2 FOREIGN KEY (category_id) REFERENCES t_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE t_conversation ADD CONSTRAINT FK_8A8E26E9F675F31B FOREIGN KEY (author_id) REFERENCES t_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE t_conversation ADD CONSTRAINT FK_8A8E26E9727ACA70 FOREIGN KEY (parent_id) REFERENCES t_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE t_conversation DROP CONSTRAINT FK_8A8E26E9727ACA70');
        $this->addSql('DROP TABLE t_conversation');
    }
}
