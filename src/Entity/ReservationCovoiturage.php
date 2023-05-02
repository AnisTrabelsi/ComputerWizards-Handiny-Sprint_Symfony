<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationCovoiturage
 *
 * @ORM\Table(name="reservation_covoiturage", indexes={@ORM\Index(name="fk_iduser_reser_cov", columns={"id_utilisateur"}), @ORM\Index(name="fk_cov1", columns={"id_cov"})})
 * @ORM\Entity
 */
class ReservationCovoiturage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reserv_cov", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReservCov;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_covoiturage", type="integer", nullable=false)
     */
    private $prixCovoiturage;

    /**
     * @var string
     *
     * @ORM\Column(name="depart", type="string", length=50, nullable=false)
     */
    private $depart;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=50, nullable=false)
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=12, nullable=false)
     */
    private $telephone;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_user")
     * })
     */
    private $idUtilisateur;

    /**
     * @var \Covoiturage
     *
     * @ORM\ManyToOne(targetEntity="Covoiturage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cov", referencedColumnName="id_cov")
     * })
     */
    private $idCov;

    public function getIdReservCov(): ?int
    {
        return $this->idReservCov;
    }

    public function getPrixCovoiturage(): ?int
    {
        return $this->prixCovoiturage;
    }

    public function setPrixCovoiturage(int $prixCovoiturage): self
    {
        $this->prixCovoiturage = $prixCovoiturage;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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
