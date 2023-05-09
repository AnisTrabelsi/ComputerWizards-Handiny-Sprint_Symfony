<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329230459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_voiture (id_reservation_voiture INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_voiture INT DEFAULT NULL, date_debut_reservation DATE NOT NULL, etat_demande_reservation VARCHAR(20) NOT NULL, description_reservation VARCHAR(100) NOT NULL, date_demande_reservation DATE NOT NULL, INDEX IDX_8E773A8A6B3CA4B (id_user), INDEX IDX_8E773A8A377F287F (id_voiture), PRIMARY KEY(id_reservation_voiture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_voiture ADD CONSTRAINT FK_8E773A8A6B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (id_user)');
        $this->addSql('ALTER TABLE reservation_voiture ADD CONSTRAINT FK_8E773A8A377F287F FOREIGN KEY (id_voiture) REFERENCES voiture (id_voiture)');
        $this->addSql('ALTER TABLE voiture CHANGE immatriculation immatriculation VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_voiture DROP FOREIGN KEY FK_8E773A8A6B3CA4B');
        $this->addSql('ALTER TABLE reservation_voiture DROP FOREIGN KEY FK_8E773A8A377F287F');
        $this->addSql('DROP TABLE reservation_voiture');
        $this->addSql('ALTER TABLE voiture CHANGE immatriculation immatriculation VARCHAR(30) DEFAULT NULL');
    }
}
