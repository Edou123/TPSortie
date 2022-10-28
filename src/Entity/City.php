<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"Veuillez renseigner un nom."
    )]
    private ?string $name = null;

    #[ORM\Column()]
    #[Assert\NotBlank(
        message:"Veuillez renseigner un code postal."
    )]
    #[Assert\Regex(
        pattern:"/\d{2}\s?\d{3}/", 
        match: true, 
        message:"Veuillez renseigner un code postal valide."
    )]
    private ?string $cp = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Place::class)]
    private Collection $place;

    public function __construct()
    {
        $this->place = new ArrayCollection();
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

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlace(): Collection
    {
        return $this->place;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->place->contains($place)) {
            $this->place->add($place);
            $place->setCity($this);
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->place->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getCity() === $this) {
                $place->setCity(null);
            }
        }

        return $this;
    }
}
