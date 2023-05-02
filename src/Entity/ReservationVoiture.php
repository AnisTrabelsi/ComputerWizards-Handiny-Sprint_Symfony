<?php

namespace App\Entity;

use App\Repository\ReservationVoitureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationVoitureRepository::class)]
class ReservationVoiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_reservation_voiture  = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de début ne doit pas être vide')]
    #[Assert\LessThan(propertyPath: "date_fin_reservation", message: 'La date de début doit être strictement inférieure à la date de fin')]
    private ?\DateTimeInterface $date_debut_reservation = null;


    

    #[ORM\Column(length: 20)]
    private ?string $etat_demande_reservation = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    #[Assert\Type(type: 'string', message: 'La description doit être une chaîne de caractères')]
    private ?string $description_reservation = null;
   


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_demande_reservation = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservationVoitures')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?User $id_user = null;
    
    #[ORM\ManyToOne(targetEntity: Voiture::class, inversedBy: 'reservationVoitures')]
    #[ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture')]
    private ?Voiture $id_voiture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de fin ne doit pas être vide')]
    private ?\DateTimeInterface $date_fin_reservation = null;
    

    public function getId(): ?int
    {
        return $this->id_reservation_voiture ;
    }

    public function getDateDebutReservation(): ?\DateTimeInterface
    {
        return $this->date_debut_reservation;
    }

    public function setDateDebutReservation(\DateTimeInterface $date_debut_reservation): self
    {
        $this->date_debut_reservation = $date_debut_reservation;

        return $this;
    }

    public function getEtatDemandeReservation(): ?string
    {
        return $this->etat_demande_reservation;
    }

    public function setEtatDemandeReservation(string $etat_demande_reservation): self
    {
        $this->etat_demande_reservation = $etat_demande_reservation;

        return $this;
    }

    public function getDescriptionReservation(): ?string
    {
        return $this->description_reservation;
    }

    public function setDescriptionReservation(string $description_reservation): self
    {
        $this->description_reservation = $description_reservation;

        return $this;
    }

    public function getDateDemandeReservation(): ?\DateTimeInterface
    {
        return $this->date_demande_reservation;
    }

    public function setDateDemandeReservation(\DateTimeInterface $date_demande_reservation): self
    {
        $this->date_demande_reservation = $date_demande_reservation;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdVoiture(): ?Voiture
    {
        return $this->id_voiture;
    }

    public function setIdVoiture(?Voiture $id_voiture): self
    {
        $this->id_voiture = $id_voiture;

        return $this;
    }

    public function getDateFinReservation(): ?\DateTimeInterface
    {
        return $this->date_fin_reservation;
    }

    public function setDateFinReservation(\DateTimeInterface $date_fin_reservation): self
    {
        $this->date_fin_reservation = $date_fin_reservation;

        return $this;
    }

   
}
