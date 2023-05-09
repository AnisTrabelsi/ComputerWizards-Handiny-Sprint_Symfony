<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323232832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F50EAE44');
        $this->addSql('DROP INDEX IDX_E9E2810F50EAE44 ON voiture');
        $this->addSql('ALTER TABLE voiture CHANGE id_utilisateur id_user INT NOT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F6B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (id_user)');
        $this->addSql('CREATE INDEX IDX_E9E2810F6B3CA4B ON voiture (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F6B3CA4B');
        $this->addSql('DROP INDEX IDX_E9E2810F6B3CA4B ON voiture');
        $this->addSql('ALTER TABLE voiture CHANGE id_user id_utilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('CREATE INDEX IDX_E9E2810F50EAE44 ON voiture (id_utilisateur)');
    }
}
