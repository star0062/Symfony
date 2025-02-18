<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218081806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_task (task_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, team_id INTEGER NOT NULL, date_create DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, difficult VARCHAR(50) NOT NULL, point INTEGER NOT NULL, color VARCHAR(50) NOT NULL, periodicity VARCHAR(50) NOT NULL, target VARCHAR(255) NOT NULL, CONSTRAINT FK_839F2F5E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_839F2F5E296CD8AE ON team_task (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE team_task');
    }
}
