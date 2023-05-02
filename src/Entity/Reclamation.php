<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_reclamation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez selectionner le type de votre reclamation ")]
    private ?string $type_reclamation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez selectionner l'etat de la reclamation ")]
  //  private ?string $etat_reclamation = null;
    private $etat_reclamation = 'en attente';

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Veuillez saisir une description ")]

    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    //private ?string $reponse = null;
    private $reponse ='Aucune réponse pour le moment';

    #[ORM\ManyToOne(inversedBy: 'reclamations',targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]

    private ?User $id_utilisateur=null ;

    public function getIdReclamation(): ?int
    {
        return $this->id_reclamation;
    }

    public function getTypeReclamation(): ?string
    {
        return $this->type_reclamation;
    }

    public function setTypeReclamation(string $type_reclamation): self
    {
        $this->type_reclamation = $type_reclamation;

        return $this;
    }

    public function getEtatReclamation(): ?string
    {
        return $this->etat_reclamation;
    }

    public function setEtatReclamation(string $etat_reclamation): self
    {
        $this->etat_reclamation = $etat_reclamation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

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
}
