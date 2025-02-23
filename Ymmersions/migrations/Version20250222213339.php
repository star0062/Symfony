<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250222213339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes password, score et roles à la table user';
    }

    public function up(Schema $schema): void
    {
        // Créer une table temporaire pour sauvegarder les données existantes
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, score, roles FROM user');

        // Supprimer la table 'user' originale
        $this->addSql('DROP TABLE user');

        // Créer la nouvelle table 'user' avec les nouvelles colonnes
        $this->addSql('CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            score INTEGER DEFAULT 0 NOT NULL,
            roles TEXT DEFAULT \'[]\' NOT NULL
        )');

        // Re-insérer les données de la table temporaire dans la nouvelle table 'user'
        $this->addSql('INSERT INTO user (id, username, email, score, roles)
                        SELECT id, username, email, score, roles FROM __temp__user');

        // Supprimer la table temporaire
        $this->addSql('DROP TABLE __temp__user');

        // Ajouter des indices uniques sur les colonnes 'username' et 'email' (si pas déjà fait)
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // Si tu veux annuler cette migration, rétablis l'ancienne structure de la table 'user'
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, score, roles FROM user');

        // Supprimer la table 'user'
        $this->addSql('DROP TABLE user');

        // Créer la table 'user' d'origine sans les nouvelles colonnes 'password', 'score', et 'roles'
        $this->addSql('CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            score INTEGER DEFAULT 0 NOT NULL,
            roles TEXT DEFAULT \'[]\' NOT NULL
        )');

        // Réinsérer les données depuis la table temporaire
        $this->addSql('INSERT INTO user (id, username, email, score, roles)
                        SELECT id, username, email, score, roles FROM __temp__user');

        // Supprimer la table temporaire
        $this->addSql('DROP TABLE __temp__user');
    }
}
