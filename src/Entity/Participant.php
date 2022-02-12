<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity(fields={"mail"}, message="Votre mail est déjà utilisé")
 * @UniqueEntity(fields="pseudo", message="Votre Pseudo est déjà pris.")
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un mail")
     * @Assert\Email(
     *     message = "Votre Email '{{ value }}' est non valide."
     * )
     * @Assert\Length(
     * min = 5,
     * max = 25,
     * minMessage = "Votre mail doit contenir au minimum  {{ limit }} caractéres",
     * maxMessage = "Votre mail doit contenir au maximum {{ limit }} caractéres"
     * )
     * @ORM\Column(type="string", length=180)
     */
    private $mail;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(pattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-+]).{8,}$/i",message="Le mot de pass doit contenir au moins de 8 caractère : -Une Majuscule -Une Minuscule -Un Chiffre -Un caractère special(#?!@$%^&*-+)")
     *
     */
    private $password;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     * min = 3,
     * max = 50,
     * minMessage = "Votre nom doit contenir au minimum  {{ limit }} caractéres",
     * maxMessage = "Votre nom doit contenir au maximum {{ limit }} caractéres"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     * min = 3,
     * max = 50,
     * minMessage = "Votre prénom doit contenir au minimum  {{ limit }} caractéres",
     * maxMessage = "Votre prénom doit contenir au maximum {{ limit }} caractéres"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     * @Assert\Regex(pattern="/^(33|0)?[1-9]\d{8}$/",message="numéro de téléphone non valide")
     *
     */

    private $telephone;


    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur = 1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un pseudo")
     * @Assert\Length(
     * min = 5,
     * max = 25,
     * minMessage = "Votre pseudo doit contenir au minimum  {{ limit }} caractéres",
     * maxMessage = "Votre pseudo doit contenir au maximum {{ limit }} caractéres"
     * )
     * @ORM\Column(type="string", length=180, unique = true)
     */
    private $pseudo;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participants")
     */
    //  * @ORM\JoinTable(name="participant") // todo: LCB à supp
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }



    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        $this->sorties->removeElement($sorty);

        return $this;
    }
    // todo: à supp
   /* public function __toString()  
    {
        return $this->sorties;
    }  */
}
