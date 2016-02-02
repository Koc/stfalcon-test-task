<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160202192457 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, file_path VARCHAR(255) NOT NULL, file_size INT NOT NULL, file_mime_type VARCHAR(20) NOT NULL, file_original_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_tag (id INT AUTO_INCREMENT NOT NULL, photo_id INT NOT NULL, tag_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_8C2D8E577E9E4C8C (photo_id), INDEX IDX_8C2D8E57BAD26311 (tag_id), UNIQUE INDEX UNIQ_photo_tag (photo_id, tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_389B7832B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo_tag ADD CONSTRAINT FK_8C2D8E577E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
        $this->addSql('ALTER TABLE photo_tag ADD CONSTRAINT FK_8C2D8E57BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo_tag DROP FOREIGN KEY FK_8C2D8E577E9E4C8C');
        $this->addSql('ALTER TABLE photo_tag DROP FOREIGN KEY FK_8C2D8E57BAD26311');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE photo_tag');
        $this->addSql('DROP TABLE tag');
    }
}
