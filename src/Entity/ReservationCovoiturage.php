<?php

namespace App\Entity;

use App\Repository\ReservationCovoiturageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationCovoiturageRepository::class)]
class ReservationCovoiturage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $prix_covoiturage = null;

    #[ORM\Column(length: 50)]
    private ?string $depart = null;

    #[ORM\Column(length: 50)]
    private ?string $destination = null;

    #[ORM\ManyToOne(inversedBy: 'reservationCovoiturages')]
    private ?Covoiturage $idCov = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixCovoiturage(): ?int
    {
        return $this->prix_covoiturage;
    }

    public function setPrixCovoiturage(int $prix_covoiturage): self
    {
        $this->prix_covoiturage = $prix_covoiturage;

        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getIdCov(): ?Covoiturage
    {
        return $this->idCov;
    }

    public function setIdCov(?Covoiturage $idCov): self
    {
        $this->idCov = $idCov;

        return $this;
    }
}
