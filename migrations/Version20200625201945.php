<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200625201945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT NULL, CHANGE death death DATE DEFAULT NULL');
        $this->addSql('CREATE FULLTEXT INDEX IDX_BDAFD8C85E237E06 ON author (name)');
        // $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE alttitle alttitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE FULLTEXT INDEX IDX_D8698A762B36786B518597B148D2997C ON document (title, subtitle, alttitle)');
        // $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE fond_id fond_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) DEFAULT NULL, CHANGE isbn isbn VARCHAR(32) DEFAULT NULL, CHANGE published_at published_at DATE DEFAULT NULL, CHANGE tome tome VARCHAR(255) DEFAULT NULL, CHANGE pages pages VARCHAR(255) DEFAULT NULL');
        // $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CCF1F1BA5E237E06 ON editor (name)');
        // $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) DEFAULT NULL');
        // $this->addSql('ALTER TABLE reservation CHANGE validated_at validated_at DATETIME DEFAULT NULL, CHANGE last_edited_at last_edited_at DATETIME DEFAULT NULL, CHANGE commentaire commentaire VARCHAR(255) DEFAULT NULL');
        // $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_BDAFD8C85E237E06 ON author');
        // $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT \'NULL\', CHANGE death death DATE DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX IDX_D8698A762B36786B518597B148D2997C ON document');
        // $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE alttitle alttitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        // $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE fond_id fond_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE isbn isbn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE published_at published_at DATE DEFAULT \'NULL\', CHANGE tome tome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE pages pages VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_CCF1F1BA5E237E06 ON editor');
        // $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        // $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        // $this->addSql('ALTER TABLE reservation CHANGE validated_at validated_at DATETIME DEFAULT \'NULL\', CHANGE last_edited_at last_edited_at DATETIME DEFAULT \'NULL\', CHANGE commentaire commentaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        // $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE firstname firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
