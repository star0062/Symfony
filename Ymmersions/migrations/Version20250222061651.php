<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222061651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "group" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, score INTEGER DEFAULT 0 NOT NULL, CONSTRAINT FK_6DC044C561220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) ON DELETE CASCADE)');
        $this->addSql('CREATE INDEX IDX_6DC044C561220EA6 ON "group" (creator_id)');
    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "group"');
    }
}
