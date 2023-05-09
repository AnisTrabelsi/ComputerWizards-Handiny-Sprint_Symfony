<?php

namespace App\Entity;

use App\Repository\ReservationCovoiturageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    // #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'reservationCovoiturages')]
    // private Collection $id_utilisateur;

    // #[ORM\ManyToMany(targetEntity: User::class)]
    // #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id_user")]
    // private ?Collection $id_utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'reservationCovoiturages', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
    private ?User $id_utilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

   

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

    public function getIdUtilisateur(): ?User
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?User $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }
    

    
    


    public function addIdUtilisateur(User $idUtilisateur): self
    {
        if (!$this->id_utilisateur->contains($idUtilisateur)) {
            $this->id_utilisateur->add($idUtilisateur);
        }

        return $this;
    }

    public function removeIdUtilisateur(User $idUtilisateur): self
    {
        $this->id_utilisateur->removeElement($idUtilisateur);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
