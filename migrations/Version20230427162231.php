<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427162231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dons_favoris (id INT AUTO_INCREMENT NOT NULL, id_don INT DEFAULT NULL, id_utilisateur INT DEFAULT NULL, date_ajout DATE NOT NULL, INDEX IDX_DF4C21366546983 (id_don), INDEX IDX_DF4C21350EAE44 (id_utilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dons_favoris ADD CONSTRAINT FK_DF4C21366546983 FOREIGN KEY (id_don) REFERENCES don (id_don)');
        $this->addSql('ALTER TABLE dons_favoris ADD CONSTRAINT FK_DF4C21350EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commeUser');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY fk_commentaire_sujet');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY fk_utilisateur_covoiturage');
        $this->addSql('ALTER TABLE favoris_voitures DROP FOREIGN KEY favoris_voitures_ibfk_1');
        $this->addSql('ALTER TABLE favoris_voitures DROP FOREIGN KEY favoris_voitures_ibfk_2');
        $this->addSql('ALTER TABLE note_voitures DROP FOREIGN KEY note_voitures_ibfk_1');
        $this->addSql('ALTER TABLE note_voitures DROP FOREIGN KEY note_voitures_ibfk_2');
        $this->addSql('ALTER TABLE postssauvegardés DROP FOREIGN KEY sauuser');
        $this->addSql('ALTER TABLE postssauvegardés DROP FOREIGN KEY sausujet');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_iduser_reclam');
        $this->addSql('ALTER TABLE reservation_chauffeur DROP FOREIGN KEY fk_chauffeur2');
        $this->addSql('ALTER TABLE reservation_covoiturage DROP FOREIGN KEY fk_cov1');
        $this->addSql('ALTER TABLE reservation_covoiturage DROP FOREIGN KEY fk_iduser_reser_cov');
        $this->addSql('ALTER TABLE reservation_voiture DROP FOREIGN KEY reservation_voiture_ibfk_2');
        $this->addSql('ALTER TABLE reservation_voiture DROP FOREIGN KEY reservation_voiture_ibfk_1');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK4');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK2');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY voiture_ibfk_1');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE chauffeur');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE covoiturage');
        $this->addSql('DROP TABLE favoris_voitures');
        $this->addSql('DROP TABLE note_voitures');
        $this->addSql('DROP TABLE postssauvegardés');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation_chauffeur');
        $this->addSql('DROP TABLE reservation_covoiturage');
        $this->addSql('DROP TABLE reservation_voiture');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY fk_don_demande_don');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY fk_utilisateur_demand_don');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY fk_don_demande_don');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY fk_utilisateur_demand_don');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT FK_80C8984266546983 FOREIGN KEY (id_don) REFERENCES don (id_don)');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT FK_80C8984250EAE44 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user)');
        $this->addSql('DROP INDEX fk_don_demande_don ON demande_don');
        $this->addSql('CREATE INDEX IDX_80C8984266546983 ON demande_don (id_don)');
        $this->addSql('DROP INDEX fk_utilisateur_demand_don ON demande_don');
        $this->addSql('CREATE INDEX IDX_80C8984250EAE44 ON demande_don (id_utilisateur)');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT fk_don_demande_don FOREIGN KEY (id_don) REFERENCES don (id_don) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT fk_utilisateur_demand_don FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
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
        $this->addSql('CREATE TABLE categorie (id_categorie INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_creation_categorie DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, nb_sujets INT DEFAULT NULL, PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE chauffeur (id_chauffeur INT AUTO_INCREMENT NOT NULL, CIN VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Nom VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Adresse VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Statut_disponibilite TINYINT(1) NOT NULL, PRIMARY KEY(id_chauffeur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commentaire (id_commentaire INT AUTO_INCREMENT NOT NULL, id_sujet INT NOT NULL, id_utilisateur INT NOT NULL, contenu_commentaire VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_publication DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, nb_mentions INT NOT NULL, piecejointe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_commentaire_sujet (id_sujet), INDEX commeUser (id_utilisateur), PRIMARY KEY(id_commentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE covoiturage (id_cov INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, depart VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, destination VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_covoiturage DATE NOT NULL, Prix INT DEFAULT NULL, nbrplace INT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, telephone VARCHAR(12) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_utilisateur_covoiturage (id_utilisateur), PRIMARY KEY(id_cov)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favoris_voitures (id_favoris_voitures INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_voiture INT NOT NULL, date_ajout_favoris DATE NOT NULL, INDEX id_user (id_user), INDEX id_voiture (id_voiture), PRIMARY KEY(id_favoris_voitures)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE note_voitures (id_note_voitures INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_voiture INT NOT NULL, note DOUBLE PRECISION NOT NULL, INDEX note_voitures_ibfk_1 (id_user), INDEX note_voitures_ibfk_2 (id_voiture), PRIMARY KEY(id_note_voitures)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE postssauvegardés (id_post_sauvegarde INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, id_sujet INT NOT NULL, date_ajout_sauvegarde DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, INDEX sausujet (id_sujet), INDEX sauuser (id_utilisateur), PRIMARY KEY(id_post_sauvegarde)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id_reclamation INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, type_reclamation VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etat_reclamation VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reponse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_iduser_reclam (id_utilisateur), PRIMARY KEY(id_reclamation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_chauffeur (id_reservation_chauffeur INT AUTO_INCREMENT NOT NULL, id_chauffeur INT NOT NULL, duree_service INT NOT NULL, date_prise_en_charge DATE NOT NULL, description_demande VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_chauffeur2 (id_chauffeur), PRIMARY KEY(id_reservation_chauffeur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_covoiturage (id_reserv_cov INT AUTO_INCREMENT NOT NULL, id_cov INT NOT NULL, id_utilisateur INT NOT NULL, prix_covoiturage INT NOT NULL, depart VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, destination VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, telephone VARCHAR(12) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_cov1 (id_cov), INDEX fk_iduser_reser_cov (id_utilisateur), PRIMARY KEY(id_reserv_cov)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_voiture (id_reservation_voiture INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_voiture INT NOT NULL, date_debut_reservation DATE NOT NULL, date_fin_reservation DATE NOT NULL, etat_demande_reservation VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description_reservation VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_demande_reservation DATE NOT NULL, INDEX id_voiture (id_voiture), INDEX id_user (id_user), PRIMARY KEY(id_reservation_voiture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sujet (id_sujet INT AUTO_INCREMENT NOT NULL, id_categorie INT NOT NULL, id_utilisateur INT NOT NULL, titre_sujet VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_creation_sujet DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, contenu_sujet VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nb_commentaires INT NOT NULL, etat VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, tags VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX FK2 (id_categorie), INDEX FK4 (id_utilisateur), PRIMARY KEY(id_sujet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE voiture (id_voiture INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, immatriculation VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, marque VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, modele VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_validation_technique DATE NOT NULL, boite_vitesse VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, kilometrage VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, carburant VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image_voiture VARCHAR(990) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix_location DOUBLE PRECISION NOT NULL, INDEX voiture_ibfk_1 (id_user), PRIMARY KEY(id_voiture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commeUser FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fk_commentaire_sujet FOREIGN KEY (id_sujet) REFERENCES sujet (id_sujet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT fk_utilisateur_covoiturage FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_voitures ADD CONSTRAINT favoris_voitures_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_voitures ADD CONSTRAINT favoris_voitures_ibfk_2 FOREIGN KEY (id_voiture) REFERENCES voiture (id_voiture) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_voitures ADD CONSTRAINT note_voitures_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_voitures ADD CONSTRAINT note_voitures_ibfk_2 FOREIGN KEY (id_voiture) REFERENCES voiture (id_voiture) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postssauvegardés ADD CONSTRAINT sauuser FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postssauvegardés ADD CONSTRAINT sausujet FOREIGN KEY (id_sujet) REFERENCES sujet (id_sujet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_iduser_reclam FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_chauffeur ADD CONSTRAINT fk_chauffeur2 FOREIGN KEY (id_chauffeur) REFERENCES chauffeur (id_chauffeur)');
        $this->addSql('ALTER TABLE reservation_covoiturage ADD CONSTRAINT fk_cov1 FOREIGN KEY (id_cov) REFERENCES covoiturage (id_cov) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_covoiturage ADD CONSTRAINT fk_iduser_reser_cov FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_voiture ADD CONSTRAINT reservation_voiture_ibfk_2 FOREIGN KEY (id_voiture) REFERENCES voiture (id_voiture) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_voiture ADD CONSTRAINT reservation_voiture_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK4 FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK2 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT voiture_ibfk_1 FOREIGN KEY (id_user) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dons_favoris DROP FOREIGN KEY FK_DF4C21366546983');
        $this->addSql('ALTER TABLE dons_favoris DROP FOREIGN KEY FK_DF4C21350EAE44');
        $this->addSql('DROP TABLE dons_favoris');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984266546983');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984250EAE44');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984266546983');
        $this->addSql('ALTER TABLE demande_don DROP FOREIGN KEY FK_80C8984250EAE44');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT fk_don_demande_don FOREIGN KEY (id_don) REFERENCES don (id_don) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_don ADD CONSTRAINT fk_utilisateur_demand_don FOREIGN KEY (id_utilisateur) REFERENCES user (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
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
