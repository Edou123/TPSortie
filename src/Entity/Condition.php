<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConditionRepository::class)]
#[ORM\Table(name: '`condition`')]
#[ApiResource]
class Condition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:'Veuillez renseigner un libellé.'
    )]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'outingCondition', targetEntity: Outing::class)]
    private Collection $outing;

    public function __construct()
    {
        $this->outing = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Outing>
     */
    public function getOuting(): Collection
    {
        return $this->outing;
    }

    public function addOuting(Outing $outing): self
    {
        if (!$this->outing->contains($outing)) {
            $this->outing->add($outing);
            $outing->setOutingCondition($this);
        }

        return $this;
    }

    public function removeOuting(Outing $outing): self
    {
        if ($this->outing->removeElement($outing)) {
            // set the owning side to null (unless already changed)
            if ($outing->getOutingCondition() === $this) {
                $outing->setOutingCondition(null);
            }
        }

        return $this;
    }
}
