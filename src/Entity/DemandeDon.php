<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeDon
 *
 * @ORM\Table(name="demande_don", indexes={@ORM\Index(name="fk_don_demande_don", columns={"id_don"}), @ORM\Index(name="fk_utilisateur_demand_don", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class DemandeDon
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_demande_don", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDemandeDon;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_demande", type="date", nullable=false)
     */
    private $dateDemande;

    /**
     * @var string
     *
     * @ORM\Column(name="justificatif_handicap", type="string", length=100, nullable=false)
     */
    private $justificatifHandicap;

    /**
     * @var string
     *
     * @ORM\Column(name="type_produit_demande", type="string", length=100, nullable=false)
     */
    private $typeProduitDemande;

    /**
     * @var string
     *
     * @ORM\Column(name="Remarques", type="string", length=100, nullable=false)
     */
    private $remarques;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=20, nullable=false)
     */
    private $etat;

    /**
     * @var \Don
     *
     * @ORM\ManyToOne(targetEntity="Don")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_don", referencedColumnName="id_don")
     * })
     */
    private $idDon;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_user")
     * })
     */
    private $idUtilisateur;

    public function getIdDemandeDon(): ?int
    {
        return $this->idDemandeDon;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getJustificatifHandicap(): ?string
    {
        return $this->justificatifHandicap;
    }

    public function setJustificatifHandicap(string $justificatifHandicap): self
    {
        $this->justificatifHandicap = $justificatifHandicap;

        return $this;
    }

    public function getTypeProduitDemande(): ?string
    {
        return $this->typeProduitDemande;
    }

    public function setTypeProduitDemande(string $typeProduitDemande): self
    {
        $this->typeProduitDemande = $typeProduitDemande;

        return $this;
    }

    public function getRemarques(): ?string
    {
        return $this->remarques;
    }

    public function setRemarques(string $remarques): self
    {
        $this->remarques = $remarques;

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

    public function getIdDon(): ?Don
    {
        return $this->idDon;
    }

    public function setIdDon(?Don $idDon): self
    {
        $this->idDon = $idDon;

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


}
