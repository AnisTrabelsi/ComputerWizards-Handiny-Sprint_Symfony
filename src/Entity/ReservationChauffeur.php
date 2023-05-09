<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'reservation_chauffeur')]
#[ORM\Entity]
class ReservationChauffeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id_reservation_chauffeur', type: 'integer', nullable: false)]
    private int $idReservationChauffeur;

    #[ORM\Column(name: 'duree_service', type: 'integer', nullable: false)]
    #[Assert\NotBlank]
    private int $dureeService;

    #[ORM\Column(name: 'date_prise_en_charge', type: 'date', nullable: false)]
    #[Assert\NotBlank]
    private \DateTimeInterface $datePriseEnCharge;

    #[ORM\Column(name: 'description_demande', type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank]
    private string $descriptionDemande;

    #[ORM\ManyToOne(targetEntity: 'Chauffeur')]
    #[ORM\JoinColumn(name: 'id_chauffeur', referencedColumnName: 'id_chauffeur', nullable: false)]

    private ?Chauffeur $idChauffeur;


    public function getIdReservationChauffeur(): ?int
    {
        return $this->idReservationChauffeur;
    }

    public function getDureeService(): ?int
    {
        return $this->dureeService;
    }

    public function setDureeService(int $dureeService): self
    {
        $this->dureeService = $dureeService;

        return $this;
    }

    public function getDatePriseEnCharge(): ?\DateTimeInterface
    {
        return $this->datePriseEnCharge;
    }

    public function setDatePriseEnCharge(\DateTimeInterface $datePriseEnCharge): self
    {
        $this->datePriseEnCharge = $datePriseEnCharge;

        return $this;
    }

    public function getDescriptionDemande(): ?string
    {
        return $this->descriptionDemande;
    }

    public function setDescriptionDemande(string $descriptionDemande): self
    {
        $this->descriptionDemande = $descriptionDemande;

        return $this;
    }

    public function getIdChauffeur(): ?Chauffeur
    {
        return $this->idChauffeur;
    }

    public function setIdChauffeur(?Chauffeur $idChauffeur): self
    {
        $this->idChauffeur = $idChauffeur;

        return $this;
    }
}
