<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationVoiture
 *
 * @ORM\Table(name="reservation_voiture", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_voiture", columns={"id_voiture"})})
 * @ORM\Entity
 */
class ReservationVoiture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reservation_voiture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReservationVoiture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut_reservation", type="date", nullable=false)
     */
    private $dateDebutReservation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin_reservation", type="date", nullable=false)
     */
    private $dateFinReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_demande_reservation", type="string", length=20, nullable=false)
     */
    private $etatDemandeReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="description_reservation", type="string", length=100, nullable=false)
     */
    private $descriptionReservation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_demande_reservation", type="date", nullable=false)
     */
    private $dateDemandeReservation;

    /**
     * @var \Voiture
     *
     * @ORM\ManyToOne(targetEntity="Voiture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="id_voiture")
     * })
     */
    private $idVoiture;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdReservationVoiture(): ?int
    {
        return $this->idReservationVoiture;
    }

    public function getDateDebutReservation(): ?\DateTimeInterface
    {
        return $this->dateDebutReservation;
    }

    public function setDateDebutReservation(\DateTimeInterface $dateDebutReservation): self
    {
        $this->dateDebutReservation = $dateDebutReservation;

        return $this;
    }

    public function getDateFinReservation(): ?\DateTimeInterface
    {
        return $this->dateFinReservation;
    }

    public function setDateFinReservation(\DateTimeInterface $dateFinReservation): self
    {
        $this->dateFinReservation = $dateFinReservation;

        return $this;
    }

    public function getEtatDemandeReservation(): ?string
    {
        return $this->etatDemandeReservation;
    }

    public function setEtatDemandeReservation(string $etatDemandeReservation): self
    {
        $this->etatDemandeReservation = $etatDemandeReservation;

        return $this;
    }

    public function getDescriptionReservation(): ?string
    {
        return $this->descriptionReservation;
    }

    public function setDescriptionReservation(string $descriptionReservation): self
    {
        $this->descriptionReservation = $descriptionReservation;

        return $this;
    }

    public function getDateDemandeReservation(): ?\DateTimeInterface
    {
        return $this->dateDemandeReservation;
    }

    public function setDateDemandeReservation(\DateTimeInterface $dateDemandeReservation): self
    {
        $this->dateDemandeReservation = $dateDemandeReservation;

        return $this;
    }

    public function getIdVoiture(): ?Voiture
    {
        return $this->idVoiture;
    }

    public function setIdVoiture(?Voiture $idVoiture): self
    {
        $this->idVoiture = $idVoiture;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
