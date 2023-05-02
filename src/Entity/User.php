<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user=null;
   
    #[ORM\Column(length :255)]
    private ?string $nom = null ;
   
    #[ORM\Column(length:255)]
    private ?string $prenom=null;
   
    #[ORM\Column(length:8)]
    private ?string $cin=null;
   
    #[ORM\Column(length:255)]
    private ?string $email=null;
    
    #[ORM\Column(length:255)]
    private ?string $telephone=null;
   
    #[ORM\Column(length:200)]

    private ?string $login=null;

    #[ORM\Column(length:255)]
    private ?string $motDePasse=null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDeNaissance = null;
   
    #[ORM\Column(length:255)]
    private ?string $region=null;
  
    #[ORM\Column(length:255)]
    private ?string $adresse=null;  
    
    #[ORM\Column()]
    private ?int $codePostal=null;

    #[ORM\Column(length:255)]
    private ?string $role=null;

    #[ORM\Column(length:255)]
    private ?string $code=null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sujet::class)]
    private Collection $sujets;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Postssauvegardés::class)]
    private Collection $postessauvegardes;

    public function __construct()
    {
        $this->sujets = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->postessauvegardes = new ArrayCollection();
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $dateDeNaissance): self
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Sujet>
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): self
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets->add($sujet);
            $sujet->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getIdUtilisateur() === $this) {
                $sujet->setIdUtilisateur(null);
            }
        }

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
            $commentaire->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUtilisateur() === $this) {
                $commentaire->setIdUtilisateur(null);
            }
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
            $postessauvegarde->setUser($this);
        }

        return $this;
    }

    public function removePostessauvegarde(Postssauvegardés $postessauvegarde): self
    {
        if ($this->postessauvegardes->removeElement($postessauvegarde)) {
            // set the owning side to null (unless already changed)
            if ($postessauvegarde->getUser() === $this) {
                $postessauvegarde->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

}
