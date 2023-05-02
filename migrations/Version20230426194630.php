<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426194630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP user_id');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748ABF396750 FOREIGN KEY (id) REFERENCES `user` (id_user)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
      
     
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748ABF396750');
        $this->addSql('ALTER TABLE reset_password_request ADD user_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('ALTER TABLE `user` DROP is_verified, CHANGE role role VARCHAR(255) NOT NULL');
    }
}
