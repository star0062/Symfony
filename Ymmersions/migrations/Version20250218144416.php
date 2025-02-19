<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218144416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, idteam_id, pseudo, mail, password, pp, level, hp, date_create, last_update FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, team_id INTEGER DEFAULT NULL, pseudo VARCHAR(180) NOT NULL, mail VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, pp VARCHAR(255) DEFAULT NULL, level INTEGER NOT NULL, hp INTEGER NOT NULL, date_create DATETIME NOT NULL, last_update DATETIME NOT NULL, CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, team_id, pseudo, mail, password, pp, level, hp, date_create, last_update) SELECT id, idteam_id, pseudo, mail, password, pp, level, hp, date_create, last_update FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495126AC48 ON user (mail)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE INDEX IDX_8D93D649296CD8AE ON user (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, team_id, pseudo, mail, password, pp, level, hp, date_create, last_update FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idteam_id INTEGER DEFAULT NULL, pseudo VARCHAR(180) NOT NULL, mail VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, pp VARCHAR(255) DEFAULT NULL, level INTEGER NOT NULL, hp INTEGER NOT NULL, date_create DATETIME NOT NULL, last_update DATETIME NOT NULL, CONSTRAINT FK_8D93D649F6688AC0 FOREIGN KEY (idteam_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, idteam_id, pseudo, mail, password, pp, level, hp, date_create, last_update) SELECT id, team_id, pseudo, mail, password, pp, level, hp, date_create, last_update FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495126AC48 ON user (mail)');
        $this->addSql('CREATE INDEX IDX_8D93D649F6688AC0 ON user (idteam_id)');
    }
}