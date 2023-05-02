<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
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

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Voiture::class)]
    private Collection $voitures;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: ReservationVoiture::class)]
    private Collection $reservationVoitures;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: NoteVoitures::class)]
    private Collection $noteVoitures;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: FavorisVoitures::class)]
    private Collection $favorisVoitures;

   

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
        $this->reservationVoitures = new ArrayCollection();
        $this->noteVoitures = new ArrayCollection();
        $this->favorisVoitures = new ArrayCollection();
     
    }

    /*

Ces annotations définissent une relation de type OneToMany entre deux entités User et Voiture, où un utilisateur peut avoir plusieurs voitures.
 La propriété "voitures" de l'entité User est annotée avec "OneToMany", ce qui signifie que chaque instance d'utilisateur peut avoir 
 plusieurs instances de voiture associées.

    Le paramètre "mappedBy" de l'annotation indique que la clé étrangère pour la relation est stockée dans la colonne "id_user" de
     l'entité Voiture. La propriété "voitures" est initialisée avec une instance d'ArrayCollection dans le constructeur, qui sera utilisée 
     pour stocker les instances de l'entité Voiture associées à un utilisateur donné.
*/

    

   

    

    public function getId(): ?int
    {
        return $this->id_user;
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
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures->add($voiture);
            $voiture->setIdUser($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getIdUser() === $this) {
                $voiture->setIdUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @return Collection<int, ReservationVoiture>
     */
    public function getReservationVoitures(): Collection
    {
        return $this->reservationVoitures;
    }

    public function addReservationVoiture(ReservationVoiture $reservationVoiture): self
    {
        if (!$this->reservationVoitures->contains($reservationVoiture)) {
            $this->reservationVoitures->add($reservationVoiture);
            $reservationVoiture->setIdUser($this);
        }

        return $this;
    }

    public function removeReservationVoiture(ReservationVoiture $reservationVoiture): self
    {
        if ($this->reservationVoitures->removeElement($reservationVoiture)) {
            // set the owning side to null (unless already changed)
            if ($reservationVoiture->getIdUser() === $this) {
                $reservationVoiture->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NoteVoitures>
     */
    public function getNoteVoitures(): Collection
    {
        return $this->noteVoitures;
    }

    public function addNoteVoiture(NoteVoitures $noteVoiture): self
    {
        if (!$this->noteVoitures->contains($noteVoiture)) {
            $this->noteVoitures->add($noteVoiture);
            $noteVoiture->setIdUser($this);
        }

        return $this;
    }

    public function removeNoteVoiture(NoteVoitures $noteVoiture): self
    {
        if ($this->noteVoitures->removeElement($noteVoiture)) {
            // set the owning side to null (unless already changed)
            if ($noteVoiture->getIdUser() === $this) {
                $noteVoiture->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavorisVoitures>
     */
    public function getFavorisVoitures(): Collection
    {
        return $this->favorisVoitures;
    }

    public function addFavorisVoiture(FavorisVoitures $favorisVoiture): self
    {
        if (!$this->favorisVoitures->contains($favorisVoiture)) {
            $this->favorisVoitures->add($favorisVoiture);
            $favorisVoiture->setIdUser($this);
        }

        return $this;
    }

    public function removeFavorisVoiture(FavorisVoitures $favorisVoiture): self
    {
        if ($this->favorisVoitures->removeElement($favorisVoiture)) {
            // set the owning side to null (unless already changed)
            if ($favorisVoiture->getIdUser() === $this) {
                $favorisVoiture->setIdUser(null);
            }
        }

        return $this;
    }

} 