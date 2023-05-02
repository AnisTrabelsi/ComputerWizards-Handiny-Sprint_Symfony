<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323232718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FA76ED395');
        $this->addSql('DROP INDEX IDX_E9E2810FA76ED395 ON voiture');
        $this->addSql('ALTER TABLE voiture CHANGE user_id id_utilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES `user` (id_user)');
        $this->addSql('CREATE INDEX IDX_E9E2810F50EAE44 ON voiture (id_utilisateur)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F50EAE44');
        $this->addSql('DROP INDEX IDX_E9E2810F50EAE44 ON voiture');
        $this->addSql('ALTER TABLE voiture CHANGE id_utilisateur user_id INT NOT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id_user)');
        $this->addSql('CREATE INDEX IDX_E9E2810FA76ED395 ON voiture (user_id)');
    }
}
