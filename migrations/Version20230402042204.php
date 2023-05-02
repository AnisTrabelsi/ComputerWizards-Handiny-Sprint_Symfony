<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402042204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_covoiturage (id INT AUTO_INCREMENT NOT NULL, prix_covoiturage INT NOT NULL, depart VARCHAR(50) NOT NULL, destination VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covoiturage CHANGE date_covoiturage date_covoiturage DATETIME NOT NULL, CHANGE prix prix INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation_covoiturage');
        $this->addSql('ALTER TABLE covoiturage CHANGE date_covoiturage date_covoiturage DATE NOT NULL, CHANGE prix prix INT DEFAULT NULL');
    }
}
