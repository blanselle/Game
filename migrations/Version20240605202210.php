<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605202210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment (id INT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, damage INT NOT NULL, armor INT NOT NULL, worn BOOLEAN NOT NULL, shooting_range INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, encumbrance INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D338D583A76ED395 ON equipment (user_id)');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, user_id INT DEFAULT NULL, npc_id INT DEFAULT NULL, body TEXT NOT NULL, result INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7A76ED395 ON event (user_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7CA7D6B89 ON event (npc_id)');
        $this->addSql('CREATE TABLE event_viewer (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_31C5DB0771F7E88B ON event_viewer (event_id)');
        $this->addSql('CREATE INDEX IDX_31C5DB07A76ED395 ON event_viewer (user_id)');
        $this->addSql('CREATE TABLE ground (id INT NOT NULL, name VARCHAR(255) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, walkable BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE npc (id INT NOT NULL, name VARCHAR(255) NOT NULL, img_path VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, health_max INT NOT NULL, health INT NOT NULL, stamina_max INT NOT NULL, stamina INT NOT NULL, strength_max INT NOT NULL, strength INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE position (id INT NOT NULL, user_id INT DEFAULT NULL, npc_id INT DEFAULT NULL, ground_id INT DEFAULT NULL, x INT NOT NULL, y INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_462CE4F5A76ED395 ON position (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_462CE4F5CA7D6B89 ON position (npc_id)');
        $this->addSql('CREATE INDEX IDX_462CE4F51D297B0A ON position (ground_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_position ON position (x, y)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, img_path VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, health_max INT NOT NULL, health INT NOT NULL, stamina_max INT NOT NULL, stamina INT NOT NULL, strength_max INT NOT NULL, strength INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D583A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7CA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_viewer ADD CONSTRAINT FK_31C5DB0771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_viewer ADD CONSTRAINT FK_31C5DB07A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5CA7D6B89 FOREIGN KEY (npc_id) REFERENCES npc (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F51D297B0A FOREIGN KEY (ground_id) REFERENCES ground (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE equipment DROP CONSTRAINT FK_D338D583A76ED395');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7CA7D6B89');
        $this->addSql('ALTER TABLE event_viewer DROP CONSTRAINT FK_31C5DB0771F7E88B');
        $this->addSql('ALTER TABLE event_viewer DROP CONSTRAINT FK_31C5DB07A76ED395');
        $this->addSql('ALTER TABLE position DROP CONSTRAINT FK_462CE4F5A76ED395');
        $this->addSql('ALTER TABLE position DROP CONSTRAINT FK_462CE4F5CA7D6B89');
        $this->addSql('ALTER TABLE position DROP CONSTRAINT FK_462CE4F51D297B0A');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_viewer');
        $this->addSql('DROP TABLE ground');
        $this->addSql('DROP TABLE npc');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE "user"');
    }
}
