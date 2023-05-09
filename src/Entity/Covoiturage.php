<?php

namespace App\Entity;

use App\Repository\CovoiturageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;
#[ORM\Entity(repositoryClass: CovoiturageRepository::class)]
class Covoiturage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("covoiturage_list")]

    private ?int $id = null;

    //  #[ORM\Column]
    //  #[Groups("covoiturage_list")]

    //  private ?int $id_cov = null;

            

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le champ Départ ne doit pas être vide.")]
    #[Groups("covoiturage_list")]
    private ?string $depart = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le champ destination ne doit pas être vide.")]
    #[Groups("covoiturage_list")]
    private ?string $destination = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message:"date is required")]
    // #[Assert\GreaterThanOrEqual("today", message:"La date saisie ne peut pas être antérieure à la date d'aujourd'hui.")]
    #[Groups("covoiturage_list")]
    public ?\DateTimeInterface $date_covoiturage = null;

    #[ORM\Column]
    #[Assert\Regex(pattern: '/^\d+$/', message: 'Le prix doit être un nombre')]

    #[Assert\NotBlank(message:"Le champ prix ne doit pas être vide.")]
    #[Groups("covoiturage_list")]
    private ?int $Prix = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le champ nbrplace ne doit pas être vide.")]
    #[Groups("covoiturage_list")]
    private ?int $nbrplace = null;

    #[ORM\OneToMany(mappedBy: 'idCov', targetEntity: ReservationCovoiturage::class)]
    private Collection $reservationCovoiturages;

    #[ORM\Column]
    private ?int $user = null;

   

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '4')]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '4')]
    private ?string $longitude = null;

    // #[ORM\ManyToOne(inversedBy: 'covoiturages')]
    // private ?User $id_utilisateur = null;


    #[ORM\ManyToOne(inversedBy: 'covoiturages', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
    private ?User $id_utilisateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;




    public function __construct()
    {
        $this->reservationCovoiturages = new ArrayCollection();
    }

   

 

    // #[ORM\Column(length: 50)]
    // #[Assert\NotBlank(message:"nom is required")]
    // #[Groups("covoiturage_list")]
    // private ?string $nom = null;

    // #[ORM\Column(length: 12)]
    // #[Assert\NotBlank(message:"telephone is required")]
    // #[Groups("covoiturage_list")]
    // private ?string $telephone = null;

    // #[ORM\OneToMany(mappedBy: 'covoiturage', targetEntity: ReservationCovoiturage::class)]
    // private Collection $reservationCovoiturages;

    // public function __construct()
    // {
    //     $this->reservationCovoiturages = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getIdCov(): ?int
    // {
    //     return $this->id_cov;
    // }

    // public function setIdCov(int $id_cov): self
    // {
    //     $this->id_cov = $id_cov;

    //     return $this;
    // }

   
    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDateCovoiturage(): ?\DateTimeInterface
    {
        return $this->date_covoiturage;
    }

    public function setDateCovoiturage(\DateTimeInterface $date_covoiturage): self
    {
        $this->date_covoiturage = $date_covoiturage;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): self
    {
        $this->nbrplace = $nbrplace;

        return $this;
    }

    // public function getNom(): ?string
    // {
    //     return $this->nom;
    // }

    // public function setNom(string $nom): self
    // {
    //     $this->nom = $nom;

    //     return $this;
    // }

    // public function getTelephone(): ?string
    // {
    //     return $this->telephone;
    // }

    // public function setTelephone(string $telephone): self
    // {
    //     $this->telephone = $telephone;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, ReservationCovoiturage>
    //  */
    // public function getReservationCovoiturages(): Collection
    // {
    //     return $this->reservationCovoiturages;
    // }

    // public function addReservationCovoiturage(ReservationCovoiturage $reservationCovoiturage): self
    // {
    //     if (!$this->reservationCovoiturages->contains($reservationCovoiturage)) {
    //         $this->reservationCovoiturages->add($reservationCovoiturage);
    //         $reservationCovoiturage->setCovoiturage($this);
    //     }

    //     return $this;
    // }

    // public function removeReservationCovoiturage(ReservationCovoiturage $reservationCovoiturage): self
    // {
    //     if ($this->reservationCovoiturages->removeElement($reservationCovoiturage)) {
    //         // set the owning side to null (unless already changed)
    //         if ($reservationCovoiturage->getCovoiturage() === $this) {
    //             $reservationCovoiturage->setCovoiturage(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, ReservationCovoiturage>
     */
    public function getReservationCovoiturages(): Collection
    {
        return $this->reservationCovoiturages;
    }

    public function addReservationCovoiturage(ReservationCovoiturage $reservationCovoiturage): self
    {
        if (!$this->reservationCovoiturages->contains($reservationCovoiturage)) {
            $this->reservationCovoiturages->add($reservationCovoiturage);
            $reservationCovoiturage->setIdCov($this);
        }

        return $this;
    }

    public function removeReservationCovoiturage(ReservationCovoiturage $reservationCovoiturage): self
    {
        if ($this->reservationCovoiturages->removeElement($reservationCovoiturage)) {
            // set the owning side to null (unless already changed)
            if ($reservationCovoiturage->getIdCov() === $this) {
                $reservationCovoiturage->setIdCov(null);
            }
        }

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
