<?php

namespace App\Entity;

use App\Repository\FavorisVoituresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisVoituresRepository::class)]
class FavorisVoitures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_favoris_voitures = null;

    


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favorisVoitures')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?User $id_user = null;
    
    #[ORM\ManyToOne(targetEntity: Voiture::class, inversedBy: 'favorisVoitures')]
    #[ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture')]
    private ?Voiture $id_voiture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_ajout_favoris = null;

    public function getId(): ?int
    {
        return $this->id_favoris_voitures ;
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

    public function getDateAjoutFavoris(): ?\DateTimeInterface
    {
        return $this->date_ajout_favoris;
    }

    public function setDateAjoutFavoris(\DateTimeInterface $date_ajout_favoris): self
    {
        $this->date_ajout_favoris = $date_ajout_favoris;

        return $this;
    }
}
