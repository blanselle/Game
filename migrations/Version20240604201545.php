<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604201545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE armor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE armor (id INT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, armor INT NOT NULL, worn BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, encumbrance INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF27FEFCA76ED395 ON armor (user_id)');
        $this->addSql('ALTER TABLE armor ADD CONSTRAINT FK_BF27FEFCA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE armor_id_seq CASCADE');
        $this->addSql('ALTER TABLE armor DROP CONSTRAINT FK_BF27FEFCA76ED395');
        $this->addSql('DROP TABLE armor');
    }
}
