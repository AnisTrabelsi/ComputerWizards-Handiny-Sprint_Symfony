<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chauffeur
 *
 * @ORM\Table(name="chauffeur")
 * @ORM\Entity
 */
class Chauffeur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_chauffeur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idChauffeur;

    /**
     * @var string
     *
     * @ORM\Column(name="CIN", type="string", length=11, nullable=false)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=20, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="string", length=50, nullable=false)
     */
    private $adresse;

    /**
     * @var bool
     *
     * @ORM\Column(name="Statut_disponibilite", type="boolean", nullable=false)
     */
    private $statutDisponibilite;

    public function getIdChauffeur(): ?int
    {
        return $this->idChauffeur;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function isStatutDisponibilite(): ?bool
    {
        return $this->statutDisponibilite;
    }

    public function setStatutDisponibilite(bool $statutDisponibilite): self
    {
        $this->statutDisponibilite = $statutDisponibilite;

        return $this;
    }


}
