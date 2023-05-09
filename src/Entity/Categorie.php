<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
*/

#[ORM\Entity(repositoryClass: CategorieRepository::class)]

class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCategorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire.")]
    #[Assert\Regex(pattern:"/^[A-Z]/",message:"Le titre du catégorie doit commencer par une majuscule.")]
    private ?string $nomCategorie;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreationCategorie = null;

    #[ORM\Column()]
    private ?int $nbSujets;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Sujet::class)]
    private Collection $sujets;

    public function __construct()
    {
        $this->sujets = new ArrayCollection();
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    public function getDateCreationCategorie(): ?\DateTimeInterface
    {
        return $this->dateCreationCategorie;
    }

    public function setDateCreationCategorie(\DateTimeInterface $dateCreationCategorie): self
    {
        $this->dateCreationCategorie = $dateCreationCategorie;

        return $this;
    }

    public function getNbSujets(): ?int
    {
        return $this->nbSujets;
    }

    public function setNbSujets(?int $nbSujets): self
    {
        $this->nbSujets = $nbSujets;

        return $this;
    }

	/**
	 * @return int|null
	 */
	public function getIdCategorie(): ?int {
                     		return $this->idCategorie;
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
            $sujet->setIdCategorie($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getIdCategorie() === $this) {
                $sujet->setIdCategorie(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->nomCategorie;
    }
}