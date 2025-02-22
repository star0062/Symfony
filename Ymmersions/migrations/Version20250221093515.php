<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221093515 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS friend (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL)');

        // Vérifier si la colonne 'score' existe avant de l'ajouter
        $schemaManager = $this->connection->createSchemaManager();
        $columns = $schemaManager->listTableColumns('user');

        if (!array_key_exists('score', $columns)) {
            $this->addSql('ALTER TABLE "user" ADD COLUMN score INTEGER DEFAULT 0 NOT NULL'); // Définir NOT NULL directement
        }

        $this->addSql('CREATE TABLE IF NOT EXISTS messenger_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            body CLOB NOT NULL, 
            headers CLOB NOT NULL, 
            queue_name VARCHAR(190) NOT NULL, 
            created_at DATETIME NOT NULL, 
            available_at DATETIME NOT NULL, 
            delivered_at DATETIME DEFAULT NULL
        )');

        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS friend');
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');

        // Supprimer uniquement la colonne score sans toucher aux autres données de user
        $this->addSql('ALTER TABLE "user" DROP COLUMN IF EXISTS score');
    }
}
