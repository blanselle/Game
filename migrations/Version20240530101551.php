<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530101551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD health_max INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD health INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD stamina_max INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD stamina INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD strengh_max INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD strengh INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP health_max');
        $this->addSql('ALTER TABLE "user" DROP health');
        $this->addSql('ALTER TABLE "user" DROP stamina_max');
        $this->addSql('ALTER TABLE "user" DROP stamina');
        $this->addSql('ALTER TABLE "user" DROP strengh_max');
        $this->addSql('ALTER TABLE "user" DROP strengh');
    }
}
