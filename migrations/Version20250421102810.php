<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421102810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE mood_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mood_type VARCHAR(255) NOT NULL, occurred_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , feeling_list CLOB NOT NULL --(DC2Type:array)
            , sleep_quality CLOB NOT NULL --(DC2Type:array)
            , activity_list CLOB NOT NULL --(DC2Type:array)
            , best_about_today VARCHAR(255) NOT NULL, note VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_UUID ON user (uuid)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
