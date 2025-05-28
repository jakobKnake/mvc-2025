<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528133318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql(<<<'SQL'
        //    CREATE TABLE Book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL)
        // SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE History (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, action_type VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, description VARCHAR(255) NOT NULL, created DATETIME NOT NULL, CONSTRAINT FK_E80749D79D86650F FOREIGN KEY (user_id_id) REFERENCES User (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E80749D79D86650F ON History (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE User (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(45) NOT NULL, password VARCHAR(255) NOT NULL, balance NUMERIC(10, 2) NOT NULL, profile_pic VARCHAR(255) NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE Book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE History
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE User
        SQL);
    }
}
