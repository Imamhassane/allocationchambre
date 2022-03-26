<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected $nom;

    #[ORM\Column(type: 'string', length: 255)]
    protected $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    protected $login;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected $password;

    #[ORM\Column(type: 'integer')]
    protected $telephone;

    #[ORM\Column(type: 'datetime')]
    protected $dateNaissance;

    #[ORM\Column(type: 'string', length: 255)]
    protected $matricule;

  
    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Boursier::class)]
    private $boursier;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: NonBoursier::class)]
    private $nonBoursier;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'etudiants')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;


    public function __construct()
    {
        $this->boursier = new ArrayCollection();
        $this->nonBoursier = new ArrayCollection();
       
    }

   

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
  
    

    /**
     * @return Collection<int, Boursier>
     */
    public function getBoursier(): Collection
    {
        return $this->boursier;
    }

    public function addBoursier(Boursier $boursier): self
    {
        if (!$this->boursier->contains($boursier)) {
            $this->boursier[] = $boursier;
            $boursier->setEtudiant($this);
        }

        return $this;
    }

    public function removeBoursier(Boursier $boursier): self
    {
        if ($this->boursier->removeElement($boursier)) {
            // set the owning side to null (unless already changed)
            if ($boursier->getEtudiant() === $this) {
                $boursier->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, NonBoursier>
     */
    public function getNonBoursier(): Collection
    {
        return $this->nonBoursier;
    }

    public function addNonBoursier(NonBoursier $nonBoursier): self
    {
        if (!$this->nonBoursier->contains($nonBoursier)) {
            $this->nonBoursier[] = $nonBoursier;
            $nonBoursier->setEtudiant($this);
        }

        return $this;
    }

    public function removeNonBoursier(NonBoursier $nonBoursier): self
    {
        if ($this->nonBoursier->removeElement($nonBoursier)) {
            // set the owning side to null (unless already changed)
            if ($nonBoursier->getEtudiant() === $this) {
                $nonBoursier->setEtudiant(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

   
  
    

  

   

    
}
