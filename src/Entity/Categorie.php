<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_categorie", type="string", length=20, nullable=false)
     */
    private $nomCategorie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation_categorie", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCreationCategorie = 'CURRENT_TIMESTAMP';

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_sujets", type="integer", nullable=true)
     */
    private $nbSujets;

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
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


}
