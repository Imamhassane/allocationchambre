<?php

namespace App\Entity;

use App\Repository\TypeBourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeBourseRepository::class)]
class TypeBourse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\OneToMany(mappedBy: 'bourse', targetEntity: Boursier::class)]
    private $boursiers;

    public function __construct()
    {
        $this->etudiant = new ArrayCollection();
        $this->boursiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $boursier->setBourse($this);
        }

        return $this;
    }

    public function removeBoursier(Boursier $boursier): self
    {
        if ($this->boursiers->removeElement($boursier)) {
            // set the owning side to null (unless already changed)
            if ($boursier->getBourse() === $this) {
                $boursier->setBourse(null);
            }
        }

        return $this;
    }

    

  
}
