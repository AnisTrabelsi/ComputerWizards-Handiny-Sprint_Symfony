<?php

namespace App\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

use Doctrine\ORM\Mapping\Column ;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields:"email", message:"Le mail que vous avez saisi est deja existant")]
#[UniqueEntity(fields:"login", message:"Le login que vous avez saisi est deja existant")]
class User implements UserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_user", type:"integer")]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    //controle de saisie
    #[Assert\NotBlank(message:"Veuillez saisir votre nom")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Veuillez saisir votre prenom")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez sélectionner le genre")]
    private ?string $genre = null;

    #[ORM\Column(type: 'string', length: 8)]
//contrainte de saisie pour que le champ ne reste pas vide
#[Assert\NotBlank(message: 'Veuillez saisir votre numéro CIN')]
//cpontrainte de saisie pour specifier la longeur
#[Assert\Length(
    max: 8,
    min:8 ,
    maxMessage: 'Le numéro CIN ne doit pas dépasser {{ limit }} caractères',
    minMessage: 'Le numéro CIN ne doit contenir 8 caractères',

    normalizer: 'trim'
)]
// contrainte de siaise pour specifier que ca ne contient que des chiffres
#[Assert\Regex(
    pattern: '/^\d+$/',
    message: 'Le numéro CIN doit contenir uniquement des chiffres'
)]

    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez saisir votre adresse email")]
    #[Assert\Email(message: "Veuillez saisir une adresse email valide")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez saisir votre numéro de telephone")]
    #[Assert\Length(
        max: 8,
        min:8 ,

        maxMessage: 'Le numéro de telephone ne doit pas dépasser {{ limit }} caractères',
        minMessage: 'Le numéro CIN ne doit avoir que {{ limit }} caractères',

        normalizer: 'trim'
    )]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Le numéro telephone doit contenir uniquement des chiffres'
    )]
    private ?string $telephone = null;


    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: "Veuillez saisir votre login")]
    private ?string $login = null;


    
    #[ORM\Column( name: "mot_de_passe" , length: 255  )]
    #[Assert\NotBlank(message: "Veuillez saisir votre mot de passe")]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit comporter au moins 8 caractères.")]
    

   //#[Assert\EqualTo(propertyPath:"confirm_mot_de_passe",message: "Veuillez resaisir le meme mot de passe")]
    private ?string $password = null;


    #[Assert\EqualTo(propertyPath:"password",message: "Veuillez resaisir le meme mot de passe")]
    public  $confirm_password;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Veuillez selectionner une date de naissance ")]

    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez selectionner une region")]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez saisir votre adresse")]
    private ?string $adresse = null;

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez saisir votre code postal")]
    #[Assert\Regex(
        pattern: "/^\d+$/",
        message: "Le code postal doit contenir uniquement des chiffres"
    )]
   


    private ?int $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;
    #[ORM\Column(name: "role")]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'id_utilisateur', targetEntity: Reclamation::class)]
    private Collection $reclamations;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy:'id',targetEntity: ResetPasswordRequest::class)]


    



    public function getId(): ?int
    {
        return $this->id;
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

    

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
       // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getIdUtilisateur() === $this) {
                $reclamation->setIdUtilisateur(null);
            }
        }

        return $this;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function toString()
    {
        $roles = $this->getRoles();
    
        if (empty($roles)) {
            return '';
        }
    
        return implode(', ', $roles);
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
  
}
