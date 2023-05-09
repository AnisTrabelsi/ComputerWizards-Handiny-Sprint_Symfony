<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "chauffeur")]
class Chauffeur
{
    #[ORM\Column(name: "id_chauffeur", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $idChauffeur;

    #[ORM\Column(name: "CIN", type: "string", length: 11, nullable: false)]
    private string $cin;

    #[ORM\Column(name: "Nom", type: "string", length: 20, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "Adresse", type: "string", length: 50, nullable: false)]
    private string $adresse;

    #[ORM\Column(name: "Statut_disponibilite", type: "boolean", nullable: false)]
    private bool $statutDisponibilite;

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
