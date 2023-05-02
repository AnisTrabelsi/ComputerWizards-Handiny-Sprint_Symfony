<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 12)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'id_utilisateur', targetEntity: Covoiturage::class)]
    private Collection $covoiturages;

    #[ORM\OneToMany(mappedBy: 'id_utilisateur', targetEntity: ReservationCovoiturage::class)]
    private Collection $reservationCovoiturages;

    public function __construct()
    {
        $this->covoiturages = new ArrayCollection();
        $this->reservationCovoiturages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    /**
     * @return Collection<int, Covoiturage>
     */
    public function getCovoiturages(): Collection
    {
        return $this->covoiturages;
    }

    public function addCovoiturage(Covoiturage $covoiturage): self
    {
        if (!$this->covoiturages->contains($covoiturage)) {
            $this->covoiturages->add($covoiturage);
            $covoiturage->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeCovoiturage(Covoiturage $covoiturage): self
    {
        if ($this->covoiturages->removeElement($covoiturage)) {
            // set the owning side to null (unless already changed)
            if ($covoiturage->getIdUtilisateur() === $this) {
                $covoiturage->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReservationCovoiturage>
     */
    public function getReservationCovoiturages(): Collection
    {
        return $this->reservationCovoiturages;
    }

    public function addReservationCovoiturage(ReservationCovoiturage $reservationCovoiturage): self
    {
        if (!$this->reservationCovoiturages->contains($reservationCovoiturage)) {
            $this->reservationCovoiturages->add($reservationCovoiturage);
            $reservationCovoiturage->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeReservationCovoiturage(ReservationCovoiturage $reservationCovoiturage): self
    {
        if ($this->reservationCovoiturages->removeElement($reservationCovoiturage)) {
            // set the owning side to null (unless already changed)
            if ($reservationCovoiturage->getIdUtilisateur() === $this) {
                $reservationCovoiturage->setIdUtilisateur(null);
            }
        }

        return $this;
    }
}
