<?php

namespace App\Entity;

use App\Repository\BoursierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoursierRepository::class)]
class Boursier
{
   

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(message:"Le nom est obligatoire") ]
    #[Assert\length(
        min : 5,
        max : 50,
        minMessage : 'Your first name must be at least  characters long',
        maxMessage : 'Your first name cannot be longer than  characters',
    
    ) ]
    #[ORM\Column(type: 'string', length: 255)]
    private $nom;


    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le prénom est obligatoire") ]
    private $prenom;


    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le login est obligatoire") ]
    #[Assert\Email(message:"L'email est invalide") ]

    private $login;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $password;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message:"Le numéro de téléphone est obligatoire") ]
    private $telephone;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message:"Le date de naissance est obligatoire") ]
    private $dateNaissance;

    #[ORM\Column(type: 'string', length: 255)]
    private $matricule;

    #[ORM\ManyToOne(targetEntity: Chambre::class, inversedBy: 'boursiers')]
    private $chambre;

    #[ORM\ManyToOne(targetEntity: TypeBourse::class, inversedBy: 'boursiers')]
    #[ORM\JoinColumn(nullable: false)]
    private $bourse;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'bousier')]
    private $etudiant;

  

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTime $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
  

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getBourse(): ?TypeBourse
    {
        return $this->bourse;
    }

    public function setBourse(?TypeBourse $bourse): self
    {
        $this->bourse = $bourse;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

   

    
}
