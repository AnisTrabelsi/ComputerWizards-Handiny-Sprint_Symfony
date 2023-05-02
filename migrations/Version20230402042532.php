<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402042532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_covoiturage ADD id_cov_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_covoiturage ADD CONSTRAINT FK_E4AB43D5D5A36A44 FOREIGN KEY (id_cov_id) REFERENCES covoiturage (id)');
        $this->addSql('CREATE INDEX IDX_E4AB43D5D5A36A44 ON reservation_covoiturage (id_cov_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_covoiturage DROP FOREIGN KEY FK_E4AB43D5D5A36A44');
        $this->addSql('DROP INDEX IDX_E4AB43D5D5A36A44 ON reservation_covoiturage');
        $this->addSql('ALTER TABLE reservation_covoiturage DROP id_cov_id');
    }
}
