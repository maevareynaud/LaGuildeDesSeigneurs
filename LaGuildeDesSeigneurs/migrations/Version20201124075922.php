<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124075922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('CREATE INDEX IDX_3A29410E99E6F5DF ON characters (player_id)');
        $this->addSql('ALTER TABLE players CHANGE character_played character_played INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E99E6F5DF');
        $this->addSql('DROP INDEX IDX_3A29410E99E6F5DF ON characters');
        $this->addSql('ALTER TABLE characters DROP player_id');
        $this->addSql('ALTER TABLE players CHANGE character_played character_played LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\'');
    }
}
