<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use App\Entity\Sujet;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_commentaire_sujet", columns={"id_sujet"}), @ORM\Index(name="commeUser", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCommentaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le contenu est obligatoire.")]
    private ?string $contenuCommentaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column()]
    private ?int $nbMentions = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La pièce jointe est obligatoire.")]
    private ?string $piecejointe = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires', targetEntity: Sujet::class)]
    #[ORM\JoinColumn(name: 'id_sujet', referencedColumnName: 'id_sujet', nullable: false)]
    private ?Sujet $sujet = null;

    private bool $isLiked = false;

    public function getIdCommentaire(): ?int
    {
        return $this->idCommentaire;
    }

    public function getContenuCommentaire(): ?string
    {
        return $this->contenuCommentaire;
    }

    public function setContenuCommentaire(string $contenuCommentaire): self
    {
        $this->contenuCommentaire = $contenuCommentaire;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): self
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getNbMentions(): ?int
    {
        return $this->nbMentions;
    }

    public function setNbMentions(int $nbMentions): self
    {
        $this->nbMentions = $nbMentions;

        return $this;
    }

    public function getPiecejointe(): ?string
    {
        return $this->piecejointe;
    }

    public function setPiecejointe(string $piecejointe): self
    {
        $this->piecejointe = $piecejointe;

        return $this;
    }

    public function setClassroom(?User $u): self
    {
        $this->user = $u;

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

    public function getIsLiked(): bool
    {
        return $this->isLiked;
    }
    
    public function __toString()
    {
        return $this->contenuCommentaire;
    }

}
