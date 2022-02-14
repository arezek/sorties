<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez ajouter un nom au campus")
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=sortie::class, mappedBy="campus", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $campusSorties;

    /**
     * @ORM\OneToMany(targetEntity=participant::class, mappedBy="campus",orphanRemoval=true)
     */
    private $campusParticipants;

    public function __construct()
    {
        $this->campusSorties = new ArrayCollection();
        $this->campusParticipants = new ArrayCollection();
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

    /**
     * @return Collection|sortie[]
     */
    public function getCampusSorties(): Collection
    {
        return $this->campusSorties;
    }

    public function addCampusSorty(sortie $campusSorty): self
    {
        if (!$this->campusSorties->contains($campusSorty)) {
            $this->campusSorties[] = $campusSorty;
            $campusSorty->setCampus($this);
        }

        return $this;
    }

    public function removeCampusSorty(sortie $campusSorty): self
    {
        if ($this->campusSorties->removeElement($campusSorty)) {
            // set the owning side to null (unless already changed)
            if ($campusSorty->getCampus() === $this) {
                $campusSorty->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|participant[]
     */
    public function getCampusParticipants(): Collection
    {
        return $this->campusParticipants;
    }

    public function addCampusParticipant(participant $campusParticipant): self
    {
        if (!$this->campusParticipants->contains($campusParticipant)) {
            $this->campusParticipants[] = $campusParticipant;
            $campusParticipant->setCampus($this);
        }

        return $this;
    }

    public function removeCampusParticipant(participant $campusParticipant): self
    {
        if ($this->campusParticipants->removeElement($campusParticipant)) {
            // set the owning side to null (unless already changed)
            if ($campusParticipant->getCampus() === $this) {
                $campusParticipant->setCampus(null);
            }
        }

        return $this;
    }
}
