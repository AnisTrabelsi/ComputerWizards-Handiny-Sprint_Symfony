<?php

namespace App\Entity;
use App\Repository\DonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: DonRepository::class)]

class Don
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("dons")]
    private ?int $idDon = null;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:"Le type est obligatoire")]
    #[Groups("dons")]
    private ?string $type = null;

    #[ORM\Column(length: 100,nullable : true)]
    //#[Assert\NotBlank(message:"description est obligatoire")]
    #[Groups("dons")]
    private ?string $imageDon = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min:4,max:100,minMessage:"Le nombre minimum de caractère est 4",maxMessage:"le nombre maximum de caractere est 100")]
    #[Assert\NotBlank(message:"La description est obligatoire")]
    #[Assert\Regex(pattern: "/^[A-Z]/", message: "La première lettre de la description doit être en majuscule")]
    #[Groups("dons")]
    private ?string $description = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("dons")]
    private ?\DateTimeInterface $dateAjout = null;



    #[ORM\OneToMany(mappedBy: 'idDon', targetEntity: DemandeDon::class)]
    private Collection $Demandedon_don;


    #[ORM\ManyToOne(inversedBy: 'don_user', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
    #[Groups("dons")]
    private ?User $idUtilisateur = null;

  

    #[ORM\OneToMany(mappedBy: 'idDon', targetEntity: DonsFavoris::class)]
    private Collection $donsfavoris;

    public function __construct()
    {
        $this->Demandedon_don = new ArrayCollection();
 
        $this->donsfavoris = new ArrayCollection();
    }





    public function getIdDon(): ?int
    {
        return $this->idDon;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImageDon(): ?string
    {
        return $this->imageDon;
    }

    public function setImageDon(string $imageDon): self
    {
        $this->imageDon = $imageDon;

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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }



    /**
     * @return Collection<int, DemandeDon>
     */
    public function getDemandedonDon(): Collection
    {
        return $this->Demandedon_don;
    }

    public function addDemandedonDon(DemandeDon $demandedonDon): self
    {
        if (!$this->Demandedon_don->contains($demandedonDon)) {
            $this->Demandedon_don->add($demandedonDon);
            $demandedonDon->setIdDon($this);
        }

        return $this;
    }

    public function removeDemandedonDon(DemandeDon $demandedonDon): self
    {
        if ($this->Demandedon_don->removeElement($demandedonDon)) {
            // set the owning side to null (unless already changed)
            if ($demandedonDon->getIdDon() === $this) {
                $demandedonDon->setIdDon(null);
            }
        }

        return $this;
    }

    public function getIdUtilisateur(): ?User
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?User $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

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
            $donsFavori->setIdDon($this);
        }

        return $this;
    }

    public function removeDonsFavori(DonsFavoris $donsFavori): self
    {
        if ($this->donsfavoris->removeElement($donsFavori)) {
            // set the owning side to null (unless already changed)
            if ($donsFavori->getIdDon() === $this) {
                $donsFavori->setIdDon(null);
            }
        }

        return $this;
    }
}
