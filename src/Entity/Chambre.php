<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message:"Le numéro de chambre est obligatoire")]
    #[Assert\Positive(message:"Le numéro de chambre doit être au minimum 1") ]
    private $numChambre;


    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message:"Le numéro d'étage est obligatoire") ]
    #[Assert\Positive(message:"Le numéro d'étage doit être au minimum 1") ]
    private $numEtage;

   
    #[ORM\Column(type: 'string', length: 255)]
    private $etat;

    #[ORM\Column(type: 'string', length: 255)]
    private $occupation;

    #[ORM\ManyToOne(targetEntity: Pavillon::class, inversedBy: 'chambres')]
    private $pavillon;


    #[ORM\ManyToOne(targetEntity: TypeChambre::class, inversedBy: 'chambres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message:"Le type de chambre est obligatoire") ]
    private $typeChambre;

    #[ORM\OneToMany(mappedBy: 'chambre', targetEntity: Boursier::class)]
    private $boursiers;

  

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->boursiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumChambre(): ?int
    {
        return $this->numChambre;
    }

    public function setNumChambre(int $numChambre): self
    {
        $this->numChambre = $numChambre;

        return $this;
    }

    public function getNumEtage(): ?int
    {
        return $this->numEtage;
    }

    public function setNumEtage(int $numEtage): self
    {
        $this->numEtage = $numEtage;

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

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(string $occupation): self
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getPavillon(): ?Pavillon
    {
        return $this->pavillon;
    }

    public function setPavillon(?Pavillon $pavillon): self
    {
        $this->pavillon = $pavillon;

        return $this;
    }

 

    public function getTypeChambre(): ?TypeChambre
    {
        return $this->typeChambre;
    }

    public function setTypeChambre(?TypeChambre $typeChambre): self
    {
        $this->typeChambre = $typeChambre;

        return $this;
    }

    /**
     * @return Collection<int, Boursier>
     */
    public function getBoursiers(): Collection
    {
        return $this->boursiers;
    }

    public function addBoursier(Boursier $boursier): self
    {
        if (!$this->boursiers->contains($boursier)) {
            $this->boursiers[] = $boursier;
            $boursier->setChambre($this);
        }

        return $this;
    }

    public function removeBoursier(Boursier $boursier): self
    {
        if ($this->boursiers->removeElement($boursier)) {
            // set the owning side to null (unless already changed)
            if ($boursier->getChambre() === $this) {
                $boursier->setChambre(null);
            }
        }

        return $this;
    }

   
}
