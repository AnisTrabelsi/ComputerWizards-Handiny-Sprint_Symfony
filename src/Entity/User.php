<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 8)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 200)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $mot_de_passe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: DemandeDon::class, orphanRemoval: true)]
    private Collection $Demandedon_user;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Don::class, orphanRemoval: true)]
    private Collection $don_user;

   

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: DonsFavoris::class, orphanRemoval: true)]
    private Collection $donsfavoris;

    public function __construct()
    {
        $this->Demandedon_user = new ArrayCollection();
        $this->don_user = new ArrayCollection();
        $this->donsfavoris = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id_user;
    }

    public function setId(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
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
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance): self
    {
        $this->date_de_naissance = $date_de_naissance;

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

    public function setAdresse(string $string): self
    {
        $this->adresse = $string;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): self
    {
        $this->code_postal = $code_postal;

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

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, DemandeDon>
     */
    public function getDemandedonUser(): Collection
    {
        return $this->Demandedon_user;
    }

    public function addDemandedonUser(DemandeDon $demandedonUser): self
    {
        if (!$this->Demandedon_user->contains($demandedonUser)) {
            $this->Demandedon_user->add($demandedonUser);
            $demandedonUser->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeDemandedonUser(DemandeDon $demandedonUser): self
    {
        if ($this->Demandedon_user->removeElement($demandedonUser)) {
            // set the owning side to null (unless already changed)
            if ($demandedonUser->getIdUtilisateur() === $this) {
                $demandedonUser->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDonUser(): Collection
    {
        return $this->don_user;
    }

    public function addDonUser(Don $donUser): self
    {
        if (!$this->don_user->contains($donUser)) {
            $this->don_user->add($donUser);
            $donUser->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeDonUser(Don $donUser): self
    {
        if ($this->don_user->removeElement($donUser)) {
            // set the owning side to null (unless already changed)
            if ($donUser->getIdUtilisateur() === $this) {
                $donUser->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DonsFavoris>
     */
    public function getdonsfavoris(): Collection
    {
        return $this->donsfavoris;
    }

    public function addDonsFavori(DonsFavoris $donsFavori): self
    {
        if (!$this->donsfavoris->contains($donsFavori)) {
            $this->donsfavoris->add($donsFavori);
            $donsFavori->setIdUser($this);
        }

        return $this;
    }

    public function removeDonsFavori(DonsFavoris $donsFavori): self
    {
        if ($this->donsfavoris->removeElement($donsFavori)) {
            // set the owning side to null (unless already changed)
            if ($donsFavori->getIdUser() === $this) {
                $donsFavori->setIdUser(null);
            }
        }

        return $this;
    }
}