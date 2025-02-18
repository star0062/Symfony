<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218082918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, pseudo, email, roles, password, profile_photo, level, health_point, creation_date, last_update, id_team FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, level INTEGER NOT NULL, health_point INTEGER NOT NULL, creation_date DATETIME NOT NULL, last_update DATETIME NOT NULL, id_team INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, pseudo, email, roles, password, profile_picture, level, health_point, creation_date, last_update, id_team) SELECT id, pseudo, email, roles, password, profile_photo, level, health_point, creation_date, last_update, id_team FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, pseudo, email, roles, password, profile_picture, level, health_point, creation_date, last_update, id_team FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, profile_photo VARCHAR(255) DEFAULT NULL, level INTEGER NOT NULL, health_point INTEGER NOT NULL, creation_date DATETIME NOT NULL, last_update DATETIME NOT NULL, id_team INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, pseudo, email, roles, password, profile_photo, level, health_point, creation_date, last_update, id_team) SELECT id, pseudo, email, roles, password, profile_picture, level, health_point, creation_date, last_update, id_team FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
