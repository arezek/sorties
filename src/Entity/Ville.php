<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="ville", orphanRemoval=true)
     */
    private $Villelieux;

    public function __construct()
    {
        $this->Villelieux = new ArrayCollection();
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

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getVillelieux(): Collection
    {
        return $this->Villelieux;
    }

    public function addVillelieux(Lieu $villelieux): self
    {
        if (!$this->Villelieux->contains($villelieux)) {
            $this->Villelieux[] = $villelieux;
            $villelieux->setVille($this);
        }

        return $this;
    }

    public function removeVillelieux(Lieu $villelieux): self
    {
        if ($this->Villelieux->removeElement($villelieux)) {
            // set the owning side to null (unless already changed)
            if ($villelieux->getVille() === $this) {
                $villelieux->setVille(null);
            }
        }

        return $this;
    }
}
