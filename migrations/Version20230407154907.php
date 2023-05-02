<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407154907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note_voitures (id_note_voitures INT AUTO_INCREMENT NOT NULL, id_voiture INT DEFAULT NULL, id_user INT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, INDEX IDX_9B03EC85377F287F (id_voiture), INDEX IDX_9B03EC856B3CA4B (id_user), PRIMARY KEY(id_note_voitures)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_voitures ADD CONSTRAINT FK_9B03EC85377F287F FOREIGN KEY (id_voiture) REFERENCES voiture (id_voiture)');
        $this->addSql('ALTER TABLE note_voitures ADD CONSTRAINT FK_9B03EC856B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_voitures DROP FOREIGN KEY FK_9B03EC85377F287F');
        $this->addSql('ALTER TABLE note_voitures DROP FOREIGN KEY FK_9B03EC856B3CA4B');
        $this->addSql('DROP TABLE note_voitures');
    }
}
