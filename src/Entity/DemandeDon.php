<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DemandeDonRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandeDonRepository::class)]
class DemandeDon
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("demandesdons")]
    private ?int $idDemandeDon = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("demandesdons")]
    private ?\DateTimeInterface $dateDemande = null;
   
    
    #[ORM\Column(length: 100,nullable : true)]
    #[Groups("demandesdons")]
    private ?string $justificatifHandicap = null;
  

    #[ORM\Column(length: 100)]
    #[Groups("demandesdons")]
    private ?string $typeProduitDemande = null;
  

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:"veuillez insérer du texte dans le champs Remarques ")]
    #[Assert\Length(min:4,max:100,minMessage:"Le nombre minimum de caractère est 4",maxMessage:"Le nombre maximum de caractère est 100")]
    #[Assert\Regex(pattern: "/^[A-Z]/", message: "La première lettre de du texte des remarques doit être en majuscule")]
    #[Groups("demandesdons")]
    private ?string $remarques = null;
  

    #[ORM\Column(length: 20)]
   //#[Assert\NotBlank(message:"etat est obligatoire")]
   #[Groups("demandesdons")]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'Demandedon_don', targetEntity: Don::class)]
    #[ORM\JoinColumn(name: 'id_don', referencedColumnName: 'id_don', nullable: false)]
    private ?Don $idDon = null;

    #[ORM\ManyToOne(inversedBy: 'Demandedon_user', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_user', nullable: false)]
    private ?User $idUtilisateur = null;



  

 
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
