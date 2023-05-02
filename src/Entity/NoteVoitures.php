<?php

namespace App\Entity;

use App\Repository\NoteVoituresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteVoituresRepository::class)]
class NoteVoitures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_note_voitures  = null;

   

    #[ORM\ManyToOne(targetEntity: Voiture::class, inversedBy: 'noteVoitures')]
    #[ORM\JoinColumn(name: 'id_voiture', referencedColumnName: 'id_voiture')]
    private ?Voiture $id_voiture = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'noteVoitures')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?User $id_user = null;

  

    #[ORM\Column]
    private ?float $note = null;

    public function getId(): ?int
    {
        return $this->id_note_voitures ;
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

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }
}
