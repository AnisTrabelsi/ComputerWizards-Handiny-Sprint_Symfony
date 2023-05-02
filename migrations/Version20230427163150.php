<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427163150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT FK_80C8984266546983 FOREIGN KEY (id_don) REFERENCES don (id_don)');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT FK_80C8984250EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('DROP INDEX fk_don_demande_don ON demande_don');
        $this->addSql('CREATE INDEX IDX_80C8984266546983 ON demande_don (id_don)');
        $this->addSql('DROP INDEX fk_utilisateur_demand_don ON demande_don');
        $this->addSql('CREATE INDEX IDX_80C8984250EAE44 ON demande_don (id_utilisateur)');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY fk_utilisateur_don');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY fk_utilisateur_don');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D950EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('DROP INDEX fk_utilisateur_don ON don');
        $this->addSql('CREATE INDEX IDX_F8F081D950EAE44 ON don (id_utilisateur)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT fk_utilisateur_don FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE code code VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984266546983');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984250EAE44');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984266546983');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984250EAE44');
        $this->addSql('DROP INDEX idx_80c8984266546983 ON demande_don');
        $this->addSql('CREATE INDEX fk_don_demande_don ON demande_don (id_don)');
        $this->addSql('DROP INDEX idx_80c8984250eae44 ON demande_don');
        $this->addSql('CREATE INDEX fk_utilisateur_demand_don ON demande_don (id_utilisateur)');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT FK_80C8984266546983 FOREIGN KEY (id_don) REFERENCES don (id_don)');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT FK_80C8984250EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D950EAE44');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D950EAE44');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT fk_utilisateur_don FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_f8f081d950eae44 ON don');
        $this->addSql('CREATE INDEX fk_utilisateur_don ON don (id_utilisateur)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D950EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE user CHANGE code code VARCHAR(255) DEFAULT NULL');
    }
}
