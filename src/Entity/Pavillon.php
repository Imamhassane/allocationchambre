<?php

namespace App\Entity;

use App\Repository\PavillonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PavillonRepository::class)]
class Pavillon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"Le numéro du pavillon est obligatoire") ]
    private $numPavillon;

    #[Assert\NotBlank(message:"Le nombre d'étage est obligatoire") ]
    #[ORM\Column(type: 'integer')]
    private $nbreEtage;

    #[ORM\OneToMany(mappedBy: 'pavillon', targetEntity: Chambre::class)]
    private $chambres;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumPavillon(): ?string
    {
        return $this->numPavillon;
    }

    public function setNumPavillon(string $numPavillon): self
    {
        $this->numPavillon = $numPavillon;

        return $this;
    }

    public function getNbreEtage(): ?int
    {
        return $this->nbreEtage;
    }

    public function setNbreEtage(int $nbreEtage): self
    {
        $this->nbreEtage = $nbreEtage;

        return $this;
    }

    /**
     * @return Collection<int, Chambre>
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres[] = $chambre;
            $chambre->setPavillon($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getPavillon() === $this) {
                $chambre->setPavillon(null);
            }
        }

        return $this;
    }
}
