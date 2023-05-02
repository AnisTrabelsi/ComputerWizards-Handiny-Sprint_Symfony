<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DonsFavorisRepository;
use PHPUnit\TextUI\XmlConfiguration\Groups;

#[ORM\Entity(repositoryClass: DonsFavorisRepository::class)]
class DonsFavoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("donsfavoris")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Don::class, inversedBy: 'donsfavoris')]
    #[ORM\JoinColumn(name: 'id_don', referencedColumnName: 'id_don')]
    #[Groups("donsfavoris")]
    private ?Don $id_don = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'donsfavoris')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    #[Groups("donsfavoris")]
    private ?User $id_utilisateur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("donsfavoris")]
    private ?\DateTimeInterface $date_ajout = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDon(): ?Don
    {
        return $this->id_don;
    }

    public function setIdDon(?Don $id_don): self
    {
        $this->id_don = $id_don;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_utilisateur;
    }

    public function setIdUser(?User $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }
}
