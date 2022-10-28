<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\API\GetUserUsername;
use App\Controller\UserByUsernameController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(operations:[
    new Get(),
    new Put(),
    new Delete(),
    new GetCollection(),
    new Post(),
    new Patch(),
    new Get(
        uriTemplate: '/user/{username}',
        controller: UserByUsernameController::class,
        name: 'user_by_username',
    )
])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ApiProperty()]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[ApiProperty()]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un email.'
    )]
    #[Assert\Email(
        message:'Veuillez renseigner un email correct.'
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un mot de passe.'
    )]
    private ?string $password = null;
    
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un email.'
    )]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un nom.'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un prénom.'
    )]
    private ?string $firstname = null;

    #[ORM\Column()]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un n° de téléphone.'
    )]
    #[Assert\Regex(
        pattern: "/^((?:\+33\s|0)(\(0\))?[1-9](?:\s?\d{2}){4})$/",
        match: true, 
        message:"Veuillez renseigner un n° de téléphone valide."
    )]
    private ?string $phone = null;

    #[ORM\Column]
    // #[Assert\NotBlank(
    //     message:'Veuillez renseigner si l\utilisateur est un administrateur.'
    // )]
    private ?bool $administrator = null;

    #[ORM\Column]
    // #[Assert\NotBlank(
    //     message:'Veuillez renseigner l\'utilisateur est actif ou non.'
    // )]
    private ?bool $actif = null;

    #[ORM\OneToMany(mappedBy: 'organizer', targetEntity: Outing::class)]
    private Collection $outingsOrganizer;

    #[ORM\ManyToMany(targetEntity: Outing::class, mappedBy: 'registereds')]
    private Collection $outings;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    
    public function __construct()
    {
        $this->outingsOrganizer = new ArrayCollection();
        $this->outings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if($this->administrator === true){
            $roles[] = 'ROLE_ADMINISTRATEUR';
        } else {
            $roles[] = 'ROLE_USER';
        }
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOutingsOrganizer(): Collection
    {
        return $this->outingsOrganizer;
    }

    public function addOutingsOrganizer(Outing $outingsOrganizer): self
    {
        if (!$this->outingsOrganizer->contains($outingsOrganizer)) {
            $this->outingsOrganizer->add($outingsOrganizer);
            $outingsOrganizer->setOrganizer($this);
        }

        return $this;
    }

    public function removeOutingsOrganizer(Outing $outingsOrganizer): self
    {
        if ($this->outingsOrganizer->removeElement($outingsOrganizer)) {
            // set the owning side to null (unless already changed)
            if ($outingsOrganizer->getOrganizer() === $this) {
                $outingsOrganizer->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOutings(): Collection
    {
        return $this->outings;
    }

    public function addOuting(Outing $outing): self
    {
        if (!$this->outings->contains($outing)) {
            $this->outings->add($outing);
            $outing->addRegistered($this);
        }

        return $this;
    }

    public function removeOuting(Outing $outing): self
    {
        if ($this->outings->removeElement($outing)) {
            $outing->removeRegistered($this);
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

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

}
