<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutingRepository::class)]
#[ApiResource]
class Outing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHourStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLimitRegistration = null;

    #[ORM\Column]
    private ?int $nbRegistrationMax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $infosOuting = null;

    #[ORM\ManyToOne(inversedBy: 'outingsOrganizer')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Organizer = null;

    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'outings')]
    private Collection $registereds;

    #[ORM\ManyToOne(inversedBy: 'CampusOuting')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'outing')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'outing')]
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

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
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
        return $this->Organizer;
    }

    public function setOrganizer(?User $Organizer): self
    {
        $this->Organizer = $Organizer;

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
