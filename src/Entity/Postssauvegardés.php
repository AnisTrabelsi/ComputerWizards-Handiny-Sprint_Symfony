<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostssauvegardésRepository;

/**
 * Postssauvegardés
 *
 * @ORM\Table(name="postssauvegardés", indexes={@ORM\Index(name="sausujet", columns={"id_sujet"}), @ORM\Index(name="sauuser", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: PostssauvegardésRepository::class)]
class Postssauvegardés
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPostSauvegarde = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateAjoutSauvegarde = null;

    #[ORM\ManyToOne(inversedBy: 'postessauvegardes', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'postessauvegardes', targetEntity: Sujet::class)]
    #[ORM\JoinColumn(name: 'id_sujet', referencedColumnName: 'id_sujet', nullable: false)]
    private ?Sujet $sujet = null;

    public function getIdPostSauvegarde(): ?int
    {
        return $this->idPostSauvegarde;
    }

    public function getDateAjoutSauvegarde(): ?\DateTimeInterface
    {
        return $this->dateAjoutSauvegarde;
    }

    public function setDateAjoutSauvegarde(\DateTimeInterface $dateAjoutSauvegarde): self
    {
        $this->dateAjoutSauvegarde = $dateAjoutSauvegarde;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSujet(): ?Sujet
    {
        return $this->sujet;
    }

    public function setSujet(?Sujet $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }


}
