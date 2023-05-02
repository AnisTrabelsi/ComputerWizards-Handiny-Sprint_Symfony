<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SujetRepository;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Sujet
 *
 * @ORM\Table(name="sujet", indexes={@ORM\Index(name="FK2", columns={"id_categorie"}), @ORM\Index(name="FK4", columns={"id_utilisateur"})})
 * @ORM\Entity
 */

 #[ORM\Entity(repositoryClass: SujetRepository::class)]
    class Sujet
        {
            #[ORM\Id]
            #[ORM\GeneratedValue]
            #[ORM\Column]
            private ?int $idSujet = null;
        
            #[ORM\Column(length: 255)]
            #[Assert\NotBlank(message:"Le titre est obligatoire.")]
            #[Assert\Length(max : 255, maxMessage:"Le titre ne doit pas dépasser {{ limit }} caractères.")]
            #[Assert\Regex(pattern:"/^[A-Z]/",message:"Le titre doit commencer par une majuscule.")]
            private ?string $titreSujet = null;
        
            #[ORM\Column(type: Types::DATE_MUTABLE)]
            private ?\DateTimeInterface $dateCreationSujet = null;
        
            #[ORM\Column(length: 255)]
            #[Assert\NotBlank(message:"La description est obligatoire.")]
            private ?string $contenuSujet = null;
        
            #[ORM\Column()]
            private ?int $nbCommentaires = null;
        
            #[ORM\Column(length: 255)]
            private ?string $etat = 'ouvert';
        
            #[ORM\Column(length: 255)]
            #[Assert\NotBlank(message:"Les tags sont obligatoires pour plus de visibilité.")]
            private ?string $tags = null;
        
            #[ORM\ManyToOne(inversedBy: 'sujets', targetEntity: Categorie::class)]
            #[ORM\JoinColumn(name: 'id_categorie', referencedColumnName: 'id_categorie', nullable: false)]
            #[Assert\NotBlank(message:"Veuillez choisir la catégorie.")]
            private ?Categorie $categorie = null;
        
            #[ORM\ManyToOne(inversedBy: 'sujets', targetEntity: User::class)]
            #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
            private ?User $user = null;
        
            #[ORM\OneToMany(mappedBy: 'sujet', targetEntity: Commentaire::class)]
            private Collection $commentaires;
        
            #[ORM\OneToMany(mappedBy: 'sujet', targetEntity: Postssauvegardés::class)]
            private Collection $postessauvegardes;
                        
            public function __construct()
            {
                $this->commentaires = new ArrayCollection();
                $this->postessauvegardes = new ArrayCollection();
                $this->setNbCommentaires(0);
            }
        
        
            public function getIdSujet(): ?int
            {
                return $this->idSujet;
            }
        
            public function getTitreSujet(): ?string
            {
                return $this->titreSujet;
            }
        
            public function setTitreSujet(string $titreSujet): self
            {
                $this->titreSujet = $titreSujet;
        
                return $this;
            }
        
            public function getDateCreationSujet(): ?\DateTimeInterface
            {
                return $this->dateCreationSujet;
            }
        
            public function setDateCreationSujet(\DateTimeInterface $dateCreationSujet): self
            {
                $this->dateCreationSujet = $dateCreationSujet;
        
                return $this;
            }
        
            public function getContenuSujet(): ?string
            {
                return $this->contenuSujet;
            }
        
            public function setContenuSujet(string $contenuSujet): self
            {
                $this->contenuSujet = $contenuSujet;
        
                return $this;
            }
        
            public function getNbCommentaires(): ?int
            {
                return $this->nbCommentaires;
            }
        
            public function setNbCommentaires(int $nbCommentaires): self
            {
                $this->nbCommentaires = $nbCommentaires;
        
                return $this;
            }
        
            public function getEtat(): ?string
            {
                return $this->etat;
            }
        
            public function setEtat(string $etat): self
            {
                $this->etat = $etat;
        
                return $this;
            }
        
            public function getTags(): ?string
            {
                return $this->tags;
            }

            public function getTags2(): array
            {
                return $this->tags ? explode(',', $this->tags) : [];
            }
        
            public function setTags(string $tags): self
            {
                $this->tags = $tags;
        
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
            public function getCategorie(): ?Categorie
            {
                return $this->categorie;
            }
        
            public function setCategorie(?Categorie $categorie): self
            {
                $this->categorie = $categorie;
        
                return $this;
            }
                  
            /**
             * @return Collection<int, Commentaire>
             */
            public function getCommentaires(): Collection
            {
                return $this->commentaires;
            }
               
            public function addCommentaire(Commentaire $commentaire): self
            {
                if (!$this->commentaires->contains($commentaire)) {
                    $this->commentaires->add($commentaire);
                    $commentaire->setIdSujet($this);
                }
               
                return $this;
            }
            
            public function removeCommentaire(Commentaire $commentaire): self
            {
                if ($this->commentaires->removeElement($commentaire)) {
                    // set the owning side to null (unless already changed)
                    if ($commentaire->getIdSujet() === $this) {
                        $commentaire->setIdSujet(null);
                    }
                    $this->setNbCommentaires($commentaire->getNbCommentaires - 1);
                }
            
                return $this;
            }
      
            /**
             * @return Collection<int, Postssauvegardés>
             */
            public function getPostessauvegardes(): Collection
            {
                return $this->postessauvegardes;
            }
   
            public function addPostessauvegarde(Postssauvegardés $postessauvegarde): self
            {
                if (!$this->postessauvegardes->contains($postessauvegarde)) {
                    $this->postessauvegardes->add($postessauvegarde);
                    $postessauvegarde->setSujet($this);
                }
   
                return $this;
            }

            public function removePostessauvegarde(Postssauvegardés $postessauvegarde): self
            {
                if ($this->postessauvegardes->removeElement($postessauvegarde)) {
                    // set the owning side to null (unless already changed)
                    if ($postessauvegarde->getSujet() === $this) {
                        $postessauvegarde->setSujet(null);
                    }
                }

                return $this;
            }

            public function __toString()
            {
                return $this->titreSujet;
            }
            public function decrementNbCommentaires(): self
            {
                $this->nbCommentaires--;
                return $this;
            }
        }