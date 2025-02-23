<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223102458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE habit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, habit_group_id INTEGER DEFAULT NULL, description CLOB NOT NULL, difficulty INTEGER NOT NULL, color VARCHAR(20) NOT NULL, frequency VARCHAR(100) NOT NULL, target VARCHAR(100) NOT NULL, CONSTRAINT FK_44FE217261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_44FE21729B951C42 FOREIGN KEY (habit_group_id) REFERENCES groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_44FE217261220EA6 ON habit (creator_id)');
        $this->addSql('CREATE INDEX IDX_44FE21729B951C42 ON habit (habit_group_id)');
        $this->addSql('DROP TABLE group_invitation');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('DROP TABLE habit_group');
        $this->addSql('CREATE TEMPORARY TABLE __temp__groups AS SELECT id, creator_id, name, score FROM groups');
        $this->addSql('DROP TABLE groups');
        $this->addSql('CREATE TABLE groups (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, score INTEGER NOT NULL, CONSTRAINT FK_F06D397061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO groups (id, creator_id, name, score) SELECT id, creator_id, name, score FROM __temp__groups');
        $this->addSql('DROP TABLE __temp__groups');
        $this->addSql('CREATE INDEX IDX_F06D397061220EA6 ON groups (creator_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, password, email FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, password, email) SELECT id, username, password, email FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_invitation (group_id INTEGER NOT NULL, invitation_id INTEGER NOT NULL, PRIMARY KEY(group_id, invitation_id), FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (invitation_id) REFERENCES invitation (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_26D00010FE54D947 ON group_invitation (group_id)');
        $this->addSql('CREATE INDEX IDX_26D00010A35D7AF0 ON group_invitation (invitation_id)');
        $this->addSql('CREATE TABLE group_user (group_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(group_id, user_id), FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A4C98D39FE54D947 ON group_user (group_id)');
        $this->addSql('CREATE INDEX IDX_A4C98D39A76ED395 ON group_user (user_id)');
        $this->addSql('CREATE TABLE habit_group (habit_id INTEGER NOT NULL, group_id INTEGER NOT NULL, PRIMARY KEY(habit_id, group_id), FOREIGN KEY (habit_id) REFERENCES habit (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, FOREIGN KEY (group_id) REFERENCES groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4C9946AFE7AEB3B2 ON habit_group (habit_id)');
        $this->addSql('CREATE INDEX IDX_4C9946AFFE54D947 ON habit_group (group_id)');
        $this->addSql('DROP TABLE habit');
        $this->addSql('CREATE TEMPORARY TABLE __temp__groups AS SELECT id, creator_id, name, score FROM groups');
        $this->addSql('DROP TABLE groups');
        $this->addSql('CREATE TABLE groups (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, score INTEGER DEFAULT 0 NOT NULL, FOREIGN KEY (creator_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO groups (id, creator_id, name, score) SELECT id, creator_id, name, score FROM __temp__groups');
        $this->addSql('DROP TABLE __temp__groups');
        $this->addSql('CREATE INDEX IDX_F06D397061220EA6 ON groups (creator_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL, is_verified BOOLEAN NOT NULL, score INTEGER NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, email, password) SELECT id, username, email, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
