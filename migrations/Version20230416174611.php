<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416174611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covoiturage ADD user INT NOT NULL');
        $this->addSql('ALTER TABLE reservation_covoiturage DROP FOREIGN KEY FK_E4AB43D5D5A36A44');
        $this->addSql('ALTER TABLE reservation_covoiturage ADD CONSTRAINT FK_E4AB43D5D5A36A44 FOREIGN KEY (id_cov_id) REFERENCES covoiturage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE covoiturage DROP user');
        $this->addSql('ALTER TABLE reservation_covoiturage DROP FOREIGN KEY FK_E4AB43D5D5A36A44');
        $this->addSql('ALTER TABLE reservation_covoiturage ADD CONSTRAINT FK_E4AB43D5D5A36A44 FOREIGN KEY (id_cov_id) REFERENCES covoiturage (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
