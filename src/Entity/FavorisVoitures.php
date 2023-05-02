<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * FavorisVoitures
 *
 * @ORM\Table(name="favoris_voitures", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_voiture", columns={"id_voiture"})})
 * @ORM\Entity
 */
class FavorisVoitures
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_favoris_voitures", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFavorisVoitures;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout_favoris", type="date", nullable=false)
     */
    private $dateAjoutFavoris;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    /**
     * @var \Voiture
     *
     * @ORM\ManyToOne(targetEntity="Voiture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="id_voiture")
     * })
     */
    private $idVoiture;

    public function getIdFavorisVoitures(): ?int
    {
        return $this->idFavorisVoitures;
    }

    public function getDateAjoutFavoris(): ?\DateTimeInterface
    {
        return $this->dateAjoutFavoris;
    }

    public function setDateAjoutFavoris(\DateTimeInterface $dateAjoutFavoris): self
    {
        $this->dateAjoutFavoris = $dateAjoutFavoris;

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

    public function getIdVoiture(): ?Voiture
    {
        return $this->idVoiture;
    }

    public function setIdVoiture(?Voiture $idVoiture): self
    {
        $this->idVoiture = $idVoiture;

        return $this;
    }


}
