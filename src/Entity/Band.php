<?php

namespace App\Entity;

use App\Repository\BandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: BandRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_BAND_NAME', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'There is already a band with this name')]
class Band
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, BandMembership>
     */
    #[ORM\OneToMany(targetEntity: BandMembership::class, mappedBy: 'band', orphanRemoval: true)]
    private Collection $bandMemberships;

    public function __construct()
    {
        $this->bandMemberships = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, BandMembership>
     */
    public function getBandMemberships(): Collection
    {
        return $this->bandMemberships;
    }

    public function addBandMembership(BandMembership $bandMembership): static
    {
        if (!$this->bandMemberships->contains($bandMembership)) {
            $this->bandMemberships->add($bandMembership);
            $bandMembership->setBand($this);
        }

        return $this;
    }

    public function removeBandMembership(BandMembership $bandMembership): static
    {
        if ($this->bandMemberships->removeElement($bandMembership)) {
            // set the owning side to null (unless already changed)
            if ($bandMembership->getBand() === $this) {
                $bandMembership->setBand(null);
            }
        }

        return $this;
    }
}
