<?php

namespace App\Enum;

enum BandMembershipType: string
{
    case GUEST = 'guest';
    case MEMBER = 'member';
    case ADMIN = 'admin';
    case OWNER = 'owner';
}