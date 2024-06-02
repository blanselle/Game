<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240602211233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD npc_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7CA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7CA7D6B89 ON event (npc_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7CA7D6B89');
        $this->addSql('DROP INDEX IDX_3BAE0AA7CA7D6B89');
        $this->addSql('ALTER TABLE event DROP npc_id');
    }
}
