<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407160030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris_voitures (id_favoris_voitures INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_voiture INT DEFAULT NULL, date_ajout_favoris DATE NOT NULL, INDEX IDX_FB872E3D6B3CA4B (id_user), INDEX IDX_FB872E3D377F287F (id_voiture), PRIMARY KEY(id_favoris_voitures)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris_voitures ADD CONSTRAINT FK_FB872E3D6B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (id_user)');
        $this->addSql('ALTER TABLE favoris_voitures ADD CONSTRAINT FK_FB872E3D377F287F FOREIGN KEY (id_voiture) REFERENCES voiture (id_voiture)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_voitures DROP FOREIGN KEY FK_FB872E3D6B3CA4B');
        $this->addSql('ALTER TABLE favoris_voitures DROP FOREIGN KEY FK_FB872E3D377F287F');
        $this->addSql('DROP TABLE favoris_voitures');
    }
}
