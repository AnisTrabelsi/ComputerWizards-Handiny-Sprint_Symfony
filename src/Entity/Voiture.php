<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[Vich\Uploadable]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_voiture = null;

    public function getId(): ?int
    {
        return $this->id_voiture;
    }
    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"L'immatriculation est obligatoire")]
    private ?string $immatriculation = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"La marque est obligatoire")]
    private ?string $marque = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Le modèle est obligatoire")]
    private ?string $modele = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"La boite de vitesse est obligatoire")]
    private ?string $boite_vitesse = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Le kilométrage est obligatoire")]
    #[Assert\Regex(pattern: '/^\d+$/', message: 'Le kilométrage doit être un nombre')]
    private ?string $kilometrage = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"Le carburant est obligatoire")]
    private ?string $carburant = null;

    


    #[Vich\UploadableField(mapping: 'voiture_image', fileNameProperty: 'image_voiture')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 990)]
    private ?string $image_voiture = null;

    
    

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix de location est obligatoire")]
    #[Assert\Regex(pattern: '/^\d+(\.\d{1,2})?$/', message: 'Le prix de location doit être un nombre  avec maximum 2 chiffres après la virgule')]
    #[Assert\Regex(pattern: '/^\d+(\.\d+)?$/', message: 'Le prix de location doit être un nombre positif')]

    

    private ?float $prix_location= null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"La date technique est obligatoire")]
    #[Assert\LessThanOrEqual("today", message: 'La date de validation technique doit être inférieure ou égale à la date actuelle')]
    private ?\DateTimeInterface $date_validation_technique = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"La description est obligatoire")]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'voitures', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user', nullable: false)]
    #[Assert\NotBlank]
    private ?User $id_user = null;

    #[ORM\OneToMany(mappedBy: 'id_voiture', targetEntity: ReservationVoiture::class)]
    private Collection $reservationVoitures;

    #[ORM\OneToMany(mappedBy: 'id_voiture', targetEntity: NoteVoitures::class)]
    private Collection $noteVoitures;

    #[ORM\OneToMany(mappedBy: 'id_voiture', targetEntity: FavorisVoitures::class)]
    private Collection $date_ajout_favoris;

    #[ORM\OneToMany(mappedBy: 'id_voiture', targetEntity: FavorisVoitures::class)]
    private Collection $favorisVoitures;

    public function __construct()
    {
        $this->reservationVoitures = new ArrayCollection();
        $this->noteVoitures = new ArrayCollection();
        $this->date_ajout_favoris = new ArrayCollection();
        $this->favorisVoitures = new ArrayCollection();
    }
    /*
    #[ORM\ManyToOne] indique qu'il s'agit d'une relation ManyToOne entre les deux entités: Un user peut posséder plusieurs voitures mais une
    voiture concerne un seul user donc ManyToOne men jihet Voiture et OneToMany menJihet l user
    inversedBy: 'voitures' indique que la relation ManyToOne est inverse de la relation OneToMany de l'entité "User"
     vers l'entité "Voiture", où "voitures" est le nom de l'attribut dans l'entité "User" qui contient les objets "Voiture".
     
    targetEntity: User::class indique que l'entité cible de la relation est l'entité "User".
    #[ORM\JoinColumn] indique que la relation est basée sur une clé étrangère stockée dans la table de l'entité "Voiture".
    name: 'id_user' indique le nom de la colonne qui stocke la clé étrangère.
    referencedColumnName: 'id_user' indique le nom de la colonne dans la table de l'entité "User" qui contient la clé primaire correspondante.
    nullable: false indique que la valeur de la clé étrangère ne peut pas être nulle.
    Enfin, l'attribut "id_user" est défini comme étant de type "User" et nullable (pouvant être null). Cet attribut peut être utilisé pour accéder à l'objet "User" associé à la "Voiture".


    */
    


   

   

  
   
    

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    

    public function getBoiteVitesse(): ?string
    {
        return $this->boite_vitesse;
    }

    public function setBoiteVitesse(string $boiteVitesse): self
    {
        $this->boite_vitesse= $boiteVitesse;

        return $this;
    }

    public function getKilometrage(): ?string
    {
        return $this->kilometrage;
    }

    public function setKilometrage(string $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getImageVoiture(): ?string
    {
        return $this->image_voiture;
    }

    public function setImageVoiture(string $imageVoiture): self
    {
        $this->image_voiture = $imageVoiture;

        return $this;
    }


    public function getPrixLocation(): ?float
    {
        return $this->prix_location;
    }

    public function setPrixLocation(float $prixLocation): self
    {
        $this->prix_location = $prixLocation;

        return $this;
    }

    public function getDateValidationTechnique(): ?\DateTimeInterface
    {
        return $this->date_validation_technique;
    }

    public function setDateValidationTechnique(\DateTimeInterface $dateValidationTechnique): self
    {
        $this->date_validation_technique = $dateValidationTechnique;

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

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_utilisateur): self
    {
        $this->id_user = $id_utilisateur;

        return $this;
    }

    
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

       
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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
            $reservationVoiture->setIdVoiture($this);
        }

        return $this;
    }

    public function removeReservationVoiture(ReservationVoiture $reservationVoiture): self
    {
        if ($this->reservationVoitures->removeElement($reservationVoiture)) {
            // set the owning side to null (unless already changed)
            if ($reservationVoiture->getIdVoiture() === $this) {
                $reservationVoiture->setIdVoiture(null);
            }
        }

        return $this;
    }

   
    public function __toString()
    {
        return $this->getId();
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
            $noteVoiture->setIdVoiture($this);
        }

        return $this;
    }

    public function removeNoteVoiture(NoteVoitures $noteVoiture): self
    {
        if ($this->noteVoitures->removeElement($noteVoiture)) {
            // set the owning side to null (unless already changed)
            if ($noteVoiture->getIdVoiture() === $this) {
                $noteVoiture->setIdVoiture(null);
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
            $favorisVoiture->setIdVoiture($this);
        }

        return $this;
    }

    public function removeFavorisVoiture(FavorisVoitures $favorisVoiture): self
    {
        if ($this->favorisVoitures->removeElement($favorisVoiture)) {
            // set the owning side to null (unless already changed)
            if ($favorisVoiture->getIdVoiture() === $this) {
                $favorisVoiture->setIdVoiture(null);
            }
        }

        return $this;
    }

   
    
   


}
