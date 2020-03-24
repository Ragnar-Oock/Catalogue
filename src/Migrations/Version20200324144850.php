<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324144850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth DATE DEFAULT NULL, death DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collec (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collec_edition (collec_id INT NOT NULL, edition_id INT NOT NULL, INDEX IDX_94C9AD76584D4E9A (collec_id), INDEX IDX_94C9AD7674281A5E (edition_id), PRIMARY KEY(collec_id, edition_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, alttitle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, type_id INT NOT NULL, issn VARCHAR(32) DEFAULT NULL, isbn VARCHAR(32) DEFAULT NULL, inventory_number INT NOT NULL, published_at DATE DEFAULT NULL, tome VARCHAR(255) DEFAULT NULL, pages VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_A891181FC33F7837 (document_id), INDEX IDX_A891181FC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition_author (edition_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_5E366CD074281A5E (edition_id), INDEX IDX_5E366CD0F675F31B (author_id), PRIMARY KEY(edition_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editor (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fond (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, registered_at DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collec_edition ADD CONSTRAINT FK_94C9AD76584D4E9A FOREIGN KEY (collec_id) REFERENCES collec (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collec_edition ADD CONSTRAINT FK_94C9AD7674281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition ADD CONSTRAINT FK_A891181FC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE edition ADD CONSTRAINT FK_A891181FC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE edition_author ADD CONSTRAINT FK_5E366CD074281A5E FOREIGN KEY (edition_id) REFERENCES edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE edition_author ADD CONSTRAINT FK_5E366CD0F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE edition_author DROP FOREIGN KEY FK_5E366CD0F675F31B');
        $this->addSql('ALTER TABLE collec_edition DROP FOREIGN KEY FK_94C9AD76584D4E9A');
        $this->addSql('ALTER TABLE edition DROP FOREIGN KEY FK_A891181FC33F7837');
        $this->addSql('ALTER TABLE collec_edition DROP FOREIGN KEY FK_94C9AD7674281A5E');
        $this->addSql('ALTER TABLE edition_author DROP FOREIGN KEY FK_5E366CD074281A5E');
        $this->addSql('ALTER TABLE edition DROP FOREIGN KEY FK_A891181FC54C8C93');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE collec');
        $this->addSql('DROP TABLE collec_edition');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE edition_author');
        $this->addSql('DROP TABLE editor');
        $this->addSql('DROP TABLE fond');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
    }
}
