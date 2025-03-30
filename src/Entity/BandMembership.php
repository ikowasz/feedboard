<?php

namespace App\Entity;

use App\Repository\BandMembershipRepository;
use App\Enum\BandMembershipType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BandMembershipRepository::class)]
class BandMembership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bandMemberships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Band $band = null;

    #[ORM\ManyToOne(inversedBy: 'bandMemberships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $member = null;

    #[ORM\Column(enumType: BandMembershipType::class, nullable: false)]
    private BandMembershipType $type = BandMembershipType::GUEST;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function setBand(?Band $band): static
    {
        $this->band = $band;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getType(): ?BandMembershipType
    {
        return $this->type;
    }

    public function setType(BandMembershipType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
