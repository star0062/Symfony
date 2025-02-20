<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218084806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE score (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, team_id INTEGER NOT NULL, points INTEGER NOT NULL, date DATETIME NOT NULL, CONSTRAINT FK_32993751A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_32993751296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_32993751A76ED395 ON score (user_id)');
        $this->addSql('CREATE INDEX IDX_32993751296CD8AE ON score (team_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__team_task AS SELECT task_id, team_id, date_create, title, description, difficult, point, color, periodicity, target FROM team_task');
        $this->addSql('DROP TABLE team_task');
        $this->addSql('CREATE TABLE team_task (task_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, team_id INTEGER NOT NULL, user_id INTEGER NOT NULL, date_create DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, difficult VARCHAR(50) NOT NULL, point INTEGER NOT NULL, color VARCHAR(50) NOT NULL, periodicity VARCHAR(50) NOT NULL, target VARCHAR(255) NOT NULL, CONSTRAINT FK_839F2F5E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_839F2F5EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO team_task (task_id, team_id, date_create, title, description, difficult, point, color, periodicity, target) SELECT task_id, team_id, date_create, title, description, difficult, point, color, periodicity, target FROM __temp__team_task');
        $this->addSql('DROP TABLE __temp__team_task');
        $this->addSql('CREATE INDEX IDX_839F2F5E296CD8AE ON team_task (team_id)');
        $this->addSql('CREATE INDEX IDX_839F2F5EA76ED395 ON team_task (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, pseudo, email, password, profile_picture, level, health_point, creation_date, last_update, id_team FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idteam_id INTEGER DEFAULT NULL, pseudo VARCHAR(180) NOT NULL, mail VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, pp VARCHAR(255) DEFAULT NULL, level INTEGER NOT NULL, hp INTEGER NOT NULL, date_create DATETIME NOT NULL, last_update DATETIME NOT NULL, CONSTRAINT FK_8D93D649F6688AC0 FOREIGN KEY (idteam_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, pseudo, mail, password, pp, level, hp, date_create, last_update, idteam_id) SELECT id, pseudo, email, password, profile_picture, level, health_point, creation_date, last_update, id_team FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495126AC48 ON user (mail)');
        $this->addSql('CREATE INDEX IDX_8D93D649F6688AC0 ON user (idteam_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE score');
        $this->addSql('CREATE TEMPORARY TABLE __temp__team_task AS SELECT task_id, team_id, date_create, title, description, difficult, point, color, periodicity, target FROM team_task');
        $this->addSql('DROP TABLE team_task');
        $this->addSql('CREATE TABLE team_task (task_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, team_id INTEGER NOT NULL, date_create DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, difficult VARCHAR(50) NOT NULL, point INTEGER NOT NULL, color VARCHAR(50) NOT NULL, periodicity VARCHAR(50) NOT NULL, target VARCHAR(255) NOT NULL, CONSTRAINT FK_839F2F5E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO team_task (task_id, team_id, date_create, title, description, difficult, point, color, periodicity, target) SELECT task_id, team_id, date_create, title, description, difficult, point, color, periodicity, target FROM __temp__team_task');
        $this->addSql('DROP TABLE __temp__team_task');
        $this->addSql('CREATE INDEX IDX_839F2F5E296CD8AE ON team_task (team_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, idteam_id, pseudo, mail, password, pp, level, hp, date_create, last_update FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_team INTEGER DEFAULT NULL, pseudo VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, level INTEGER NOT NULL, health_point INTEGER NOT NULL, creation_date DATETIME NOT NULL, last_update DATETIME NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO user (id, id_team, pseudo, email, password, profile_picture, level, health_point, creation_date, last_update) SELECT id, idteam_id, pseudo, mail, password, pp, level, hp, date_create, last_update FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
