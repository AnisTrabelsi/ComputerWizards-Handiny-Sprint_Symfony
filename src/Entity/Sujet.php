<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet", indexes={@ORM\Index(name="FK2", columns={"id_categorie"}), @ORM\Index(name="FK4", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class Sujet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_sujet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSujet;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_sujet", type="string", length=20, nullable=false)
     */
    private $titreSujet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation_sujet", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCreationSujet = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="contenu_sujet", type="string", length=50, nullable=false)
     */
    private $contenuSujet;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_commentaires", type="integer", nullable=false)
     */
    private $nbCommentaires;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=10, nullable=false)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=false)
     */
    private $tags;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie")
     * })
     */
    private $idCategorie;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_user")
     * })
     */
    private $idUtilisateur;

    public function getIdSujet(): ?int
    {
        return $this->idSujet;
    }

    public function getTitreSujet(): ?string
    {
        return $this->titreSujet;
    }

    public function setTitreSujet(string $titreSujet): self
    {
        $this->titreSujet = $titreSujet;

        return $this;
    }

    public function getDateCreationSujet(): ?\DateTimeInterface
    {
        return $this->dateCreationSujet;
    }

    public function setDateCreationSujet(\DateTimeInterface $dateCreationSujet): self
    {
        $this->dateCreationSujet = $dateCreationSujet;

        return $this;
    }

    public function getContenuSujet(): ?string
    {
        return $this->contenuSujet;
    }

    public function setContenuSujet(string $contenuSujet): self
    {
        $this->contenuSujet = $contenuSujet;

        return $this;
    }

    public function getNbCommentaires(): ?int
    {
        return $this->nbCommentaires;
    }

    public function setNbCommentaires(int $nbCommentaires): self
    {
        $this->nbCommentaires = $nbCommentaires;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

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
