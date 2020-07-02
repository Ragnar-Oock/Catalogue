<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200521142513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, edition_id INT NOT NULL, user_id INT NOT NULL, submimited_at DATETIME NOT NULL, validated_at DATETIME DEFAULT NULL, canceled TINYINT(1) NOT NULL, last_edited_at DATETIME DEFAULT NULL, validated TINYINT(1) NOT NULL, begining_at DATETIME NOT NULL, ending_at DATETIME NOT NULL, INDEX IDX_42C8495574281A5E (edition_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495574281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT NULL, CHANGE death death DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE alttitle alttitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) DEFAULT NULL, CHANGE isbn isbn VARCHAR(32) DEFAULT NULL, CHANGE published_at published_at DATE DEFAULT NULL, CHANGE tome tome VARCHAR(255) DEFAULT NULL, CHANGE pages pages VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT \'NULL\', CHANGE death death DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE alttitle alttitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE edition CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE isbn isbn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE published_at published_at DATE DEFAULT \'NULL\', CHANGE tome tome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE pages pages VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE firstname firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
