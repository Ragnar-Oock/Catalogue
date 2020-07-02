<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200503145127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE participation_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE writer (id INT AUTO_INCREMENT NOT NULL, participation_type_id INT NOT NULL, author_id INT NOT NULL, edition_id INT NOT NULL, INDEX IDX_97A0D8828E323D52 (participation_type_id), INDEX IDX_97A0D882F675F31B (author_id), INDEX IDX_97A0D88274281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D8828E323D52 FOREIGN KEY (participation_type_id) REFERENCES participation_type (id)');
        $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D882F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE writer ADD CONSTRAINT FK_97A0D88274281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('DROP TABLE edition_author');
        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT NULL, CHANGE death death DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE alttitle alttitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) DEFAULT NULL, CHANGE isbn isbn VARCHAR(32) DEFAULT NULL, CHANGE published_at published_at DATE DEFAULT NULL, CHANGE tome tome VARCHAR(255) DEFAULT NULL, CHANGE pages pages VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE writer DROP FOREIGN KEY FK_97A0D8828E323D52');
        $this->addSql('CREATE TABLE edition_author (edition_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_5E366CD0F675F31B (author_id), INDEX IDX_5E366CD074281A5E (edition_id), PRIMARY KEY(edition_id, author_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE edition_author ADD CONSTRAINT FK_5E366CD074281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_author ADD CONSTRAINT FK_5E366CD0F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE participation_type');
        $this->addSql('DROP TABLE writer');
        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT \'NULL\', CHANGE death death DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE alttitle alttitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE isbn isbn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE published_at published_at DATE DEFAULT \'NULL\', CHANGE tome tome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE pages pages VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE firstname firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
