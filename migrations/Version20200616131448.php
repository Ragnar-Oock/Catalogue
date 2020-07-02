<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616131448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT NULL, CHANGE death death DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) DEFAULT NULL, CHANGE alttitle alttitle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE edition ADD fond_id INT DEFAULT NULL, DROP disponibility, CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) DEFAULT NULL, CHANGE isbn isbn VARCHAR(32) DEFAULT NULL, CHANGE published_at published_at DATE DEFAULT NULL, CHANGE tome tome VARCHAR(255) DEFAULT NULL, CHANGE pages pages VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE edition ADD CONSTRAINT FK_A891181F87EEAE74 FOREIGN KEY (fond_id) REFERENCES fond (id)');
        $this->addSql('CREATE INDEX IDX_A891181F87EEAE74 ON edition (fond_id)');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE validated_at validated_at DATETIME DEFAULT NULL, CHANGE last_edited_at last_edited_at DATETIME DEFAULT NULL, CHANGE commentaire commentaire VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE registered_at registered_at DATETIME NOT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE author CHANGE birth birth DATE DEFAULT \'NULL\', CHANGE death death DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE document CHANGE subtitle subtitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE alttitle alttitle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE edition DROP FOREIGN KEY FK_A891181F87EEAE74');
        $this->addSql('DROP INDEX IDX_A891181F87EEAE74 ON edition');
        $this->addSql('ALTER TABLE edition ADD disponibility INT NOT NULL, DROP fond_id, CHANGE editor_id editor_id INT DEFAULT NULL, CHANGE issn issn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE isbn isbn VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE published_at published_at DATE DEFAULT \'NULL\', CHANGE tome tome VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE pages pages VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE editor CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE participation_type CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE validated_at validated_at DATETIME DEFAULT \'NULL\', CHANGE last_edited_at last_edited_at DATETIME DEFAULT \'NULL\', CHANGE commentaire commentaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE firstname firstname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE registered_at registered_at DATE NOT NULL, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
