<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200702124959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE edition_collec ADD CONSTRAINT FK_3B555D2C74281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE edition_collec ADD CONSTRAINT FK_3B555D2C584D4E9A FOREIGN KEY (collec_id) REFERENCES collec (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495574281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        // $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE type CHANGE description description VARCHAR(255) DEFAULT NULL');
        // $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        // $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D8828E323D52 FOREIGN KEY (participation_type_id) REFERENCES participation_type (id)');
        // $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D882F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        // $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D88274281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE edition_collec DROP FOREIGN KEY FK_3B555D2C74281A5E');
        // $this->addSql('ALTER TABLE edition_collec DROP FOREIGN KEY FK_3B555D2C584D4E9A');
        // $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495574281A5E');
        // $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE type CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        // $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        // $this->addSql('ALTER TABLE writer DROP FOREIGN KEY FK_97A0D8828E323D52');
        // $this->addSql('ALTER TABLE writer DROP FOREIGN KEY FK_97A0D882F675F31B');
        // $this->addSql('ALTER TABLE writer DROP FOREIGN KEY FK_97A0D88274281A5E');
    }
}
