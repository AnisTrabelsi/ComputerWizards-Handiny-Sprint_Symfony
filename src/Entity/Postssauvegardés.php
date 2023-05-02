<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Postssauvegardés
 *
 * @ORM\Table(name="postssauvegardés", indexes={@ORM\Index(name="sausujet", columns={"id_sujet"}), @ORM\Index(name="sauuser", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class Postssauvegardés
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_post_sauvegarde", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPostSauvegarde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout_sauvegarde", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateAjoutSauvegarde = 'CURRENT_TIMESTAMP';

    /**
     * @var \Sujet
     *
     * @ORM\ManyToOne(targetEntity="Sujet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sujet", referencedColumnName="id_sujet")
     * })
     */
    private $idSujet;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_user")
     * })
     */
    private $idUtilisateur;

    public function getIdPostSauvegarde(): ?int
    {
        return $this->idPostSauvegarde;
    }

    public function getDateAjoutSauvegarde(): ?\DateTimeInterface
    {
        return $this->dateAjoutSauvegarde;
    }

    public function setDateAjoutSauvegarde(\DateTimeInterface $dateAjoutSauvegarde): self
    {
        $this->dateAjoutSauvegarde = $dateAjoutSauvegarde;

        return $this;
    }

    public function getIdSujet(): ?Sujet
    {
        return $this->idSujet;
    }

    public function setIdSujet(?Sujet $idSujet): self
    {
        $this->idSujet = $idSujet;

        return $this;
    }

    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }


}
