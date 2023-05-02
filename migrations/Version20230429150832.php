<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230429150832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        $this->addSql('ALTER TABLE reclamation CHANGE type_reclamation type_reclamation VARCHAR(255) NOT NULL, CHANGE etat_reclamation etat_reclamation VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('DROP INDEX fk_iduser_reclam ON reclamation');
        $this->addSql('CREATE INDEX IDX_CE60640450EAE44 ON reclamation (id_utilisateur)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_iduser_reclam FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD genre VARCHAR(255) NOT NULL, ADD is_verified TINYINT(1) NOT NULL, CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748ABF396750');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640450EAE44');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640450EAE44');
        $this->addSql('ALTER TABLE reclamation CHANGE type_reclamation type_reclamation VARCHAR(25) NOT NULL, CHANGE etat_reclamation etat_reclamation VARCHAR(25) NOT NULL, CHANGE description description TEXT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_iduser_reclam FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_ce60640450eae44 ON reclamation');
        $this->addSql('CREATE INDEX fk_iduser_reclam ON reclamation (id_utilisateur)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640450EAE44 FOREIGN KEY (id_utilisateur) REFERENCES `user` (id_user)');
        $this->addSql('ALTER TABLE `user` DROP genre, DROP is_verified, CHANGE role role VARCHAR(255) NOT NULL');
    }
}
