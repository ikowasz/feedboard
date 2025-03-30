<?php

namespace App\Service;

use App\Entity\Band;
use App\Entity\BandMembership;
use App\Entity\User;
use App\Enum\BandMembershipType;
use Doctrine\ORM\EntityManagerInterface;

class BandService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function join(User $user, Band $band, BandMembershipType $type = BandMembershipType::MEMBER): BandMembership
    {
        $membership = new BandMembership();
        $membership->setBand($band);
        $membership->setMember($user);
        $this->entityManager->persist($membership);
        $this->entityManager->flush();

        return $membership;
    }
}