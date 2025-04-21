<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421192940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__mood_entry AS SELECT id, mood_type, occurred_at, feeling_list, sleep_quality, activity_list, best_about_today, note FROM mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mood_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, mood_type VARCHAR(255) NOT NULL, occurred_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , feeling_list CLOB NOT NULL --(DC2Type:array)
            , sleep_quality CLOB NOT NULL --(DC2Type:array)
            , activity_list CLOB NOT NULL --(DC2Type:array)
            , best_about_today VARCHAR(255) NOT NULL, note VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_22A0A36DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO mood_entry (id, mood_type, occurred_at, feeling_list, sleep_quality, activity_list, best_about_today, note) SELECT id, mood_type, occurred_at, feeling_list, sleep_quality, activity_list, best_about_today, note FROM __temp__mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_22A0A36DA76ED395 ON mood_entry (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__mood_entry AS SELECT id, mood_type, occurred_at, feeling_list, sleep_quality, activity_list, best_about_today, note FROM mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mood_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mood_type VARCHAR(255) NOT NULL, occurred_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , feeling_list CLOB NOT NULL --(DC2Type:array)
            , sleep_quality CLOB NOT NULL --(DC2Type:array)
            , activity_list CLOB NOT NULL --(DC2Type:array)
            , best_about_today VARCHAR(255) NOT NULL, note VARCHAR(255) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO mood_entry (id, mood_type, occurred_at, feeling_list, sleep_quality, activity_list, best_about_today, note) SELECT id, mood_type, occurred_at, feeling_list, sleep_quality, activity_list, best_about_today, note FROM __temp__mood_entry
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__mood_entry
        SQL);
    }
}
