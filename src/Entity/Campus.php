<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CampusRepository::class)]
#[ApiResource]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["user:get", "user:post", "outing:getcollection"])]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user:get", "outing:getcollection"])]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un nom.'
    )]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: Outing::class)]
    private Collection $CampusOuting;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->campusOuting = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCampus($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCampus() === $this) {
                $user->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getCampusOuting(): Collection
    {
        return $this->CampusOuting;
    }

    public function addCampusOuting(Outing $campusOuting): self
    {
        if (!$this->campusOuting->contains($campusOuting)) {
            $this->campusOuting->add($campusOuting);
            $campusOuting->setCampus($this);
        }

        return $this;
    }

    public function removeCampusOuting(Outing $campusOuting): self
    {
        if ($this->campusOuting->removeElement($campusOuting)) {
            // set the owning side to null (unless already changed)
            if ($campusOuting->getCampus() === $this) {
                $campusOuting->setCampus(null);
            }
        }

        return $this;
    }
}
