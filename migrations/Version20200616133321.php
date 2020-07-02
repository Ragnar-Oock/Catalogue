<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616133321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE edition_collec (edition_id INT NOT NULL, collec_id INT NOT NULL, INDEX IDX_3B555D2C74281A5E (edition_id), INDEX IDX_3B555D2C584D4E9A (collec_id), PRIMARY KEY(edition_id, collec_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE edition_collec ADD CONSTRAINT FK_3B555D2C74281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_collec ADD CONSTRAINT FK_3B555D2C584D4E9A FOREIGN KEY (collec_id) REFERENCES collec (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE collec_edition');
        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT NULL, CHANGE death death DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE alttitle alttitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE fond_id fond_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) DEFAULT NULL, CHANGE isbn isbn VARCHAR(32) DEFAULT NULL, CHANGE published_at published_at DATE DEFAULT NULL, CHANGE tome tome VARCHAR(255) DEFAULT NULL, CHANGE pages pages VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE validated_at validated_at DATETIME DEFAULT NULL, CHANGE last_edited_at last_edited_at DATETIME DEFAULT NULL, CHANGE commentaire commentaire VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE collec_edition (collec_id INT NOT NULL, edition_id INT NOT NULL, INDEX IDX_94C9AD7674281A5E (edition_id), INDEX IDX_94C9AD76584D4E9A (collec_id), PRIMARY KEY(collec_id, edition_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE collec_edition ADD CONSTRAINT FK_94C9AD76584D4E9A FOREIGN KEY (collec_id) REFERENCES collec (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collec_edition ADD CONSTRAINT FK_94C9AD7674281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE edition_collec');
        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT \'NULL\', CHANGE death death DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE alttitle alttitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE fond_id fond_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE isbn isbn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE published_at published_at DATE DEFAULT \'NULL\', CHANGE tome tome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE pages pages VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE validated_at validated_at DATETIME DEFAULT \'NULL\', CHANGE last_edited_at last_edited_at DATETIME DEFAULT \'NULL\', CHANGE commentaire commentaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE firstname firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
