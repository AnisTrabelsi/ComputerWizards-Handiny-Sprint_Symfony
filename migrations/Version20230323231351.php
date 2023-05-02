<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323231351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voiture (id_voiture INT AUTO_INCREMENT NOT NULL, immatriculation VARCHAR(30) DEFAULT NULL, marque VARCHAR(30) NOT NULL, modele VARCHAR(30) NOT NULL, boite_vitesse VARCHAR(30) NOT NULL, kilometrage VARCHAR(30) NOT NULL, carburant VARCHAR(20) NOT NULL, image_voiture VARCHAR(990) NOT NULL, prix_location DOUBLE PRECISION NOT NULL, date_validation_technique DATE NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id_voiture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE voiture');
    }
}
