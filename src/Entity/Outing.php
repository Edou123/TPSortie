<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OutingRepository::class)]
#[ApiResource(operations:[
    new Get(),
    new Put(),
    new Delete(),
    new GetCollection(normalizationContext:['groups'=>['outing:getcollection']]),
    new Post(),
    new Patch(),
])]
class Outing
{
    #[ORM\Id]
    #[Groups(["outing:getcollection"])]   
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["outing:getcollection"])]   
    #[Assert\NotBlank(
        message:'Veuillez renseigner un nom'
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["outing:getcollection"])]   
    #[Assert\NotBlank(
        message:'Veuillez renseigner une date-heure de début.'
    )]
    private ?\DateTimeInterface $dateHourStart = null;

    #[ORM\Column()]
    #[Groups(["outing:getcollection"])]   
    #[Assert\NotBlank(
        message:'Veuillez renseigner une durée'
    )]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["outing:getcollection"])]   
    #[Assert\NotBlank(
        message:'Veuillez renseigner une date limite pour s\'inscrire.'
    )]
    private ?\DateTimeInterface $dateLimitRegistration = null;

    #[ORM\Column]
    #[Groups(["outing:getcollection"])]   
    #[Assert\NotBlank(
        message:'Veuillez renseigner un nombre maximum d\'inscrits'
    )]
    private ?int $nbRegistrationMax = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["outing:getcollection"])]   
    private ?string $infosOuting = null;

    #[ORM\ManyToOne(inversedBy: 'outingsOrganizer', fetch: 'EAGER')]
    #[Groups(["outing:getcollection"])]   
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'outings', fetch: 'EAGER')]
    #[Groups(["outing:getcollection"])]   
    private Collection $registereds;

    #[ORM\ManyToOne(inversedBy: 'CampusOuting', fetch: 'EAGER')]
    #[Groups(["outing:getcollection"])]   
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'outing', fetch: 'EAGER')]
    #[Groups(["outing:getcollection"])]   
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'outing', fetch: 'EAGER')]
    #[Groups(["outing:getcollection"])]   
    #[ORM\JoinColumn(nullable: false)]
    private ?Condition $outingCondition = null;

    public function __construct()
    {
        $this->registereds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateHourStart(): ?\DateTimeInterface
    {
        return $this->dateHourStart;
    }

    public function setDateHourStart(\DateTimeInterface $dateHourStart): self
    {
        $this->dateHourStart = $dateHourStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        return $this->dateLimitRegistration;
    }

    public function setDateLimitRegistration(\DateTimeInterface $dateLimitRegistration): self
    {
        $this->dateLimitRegistration = $dateLimitRegistration;

        return $this;
    }

    public function getNbRegistrationMax(): ?int
    {
        return $this->nbRegistrationMax;
    }

    public function setNbRegistrationMax(int $nbRegistrationMax): self
    {
        $this->nbRegistrationMax = $nbRegistrationMax;

        return $this;
    }

    public function getInfosOuting(): ?string
    {
        return $this->infosOuting;
    }

    public function setInfosOuting(?string $infosOuting): self
    {
        $this->infosOuting = $infosOuting;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getRegistereds(): Collection
    {
        return $this->registereds;
    }

    public function addRegistered(user $registered): self
    {
        if (!$this->registereds->contains($registered)) {
            $this->registereds->add($registered);
        }

        return $this;
    }

    public function removeRegistered(user $registered): self
    {
        $this->registereds->removeElement($registered);

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

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getOutingCondition(): ?Condition
    {
        return $this->outingCondition;
    }

    public function setOutingCondition(?Condition $outingCondition): self
    {
        $this->outingCondition = $outingCondition;

        return $this;
    }

}
