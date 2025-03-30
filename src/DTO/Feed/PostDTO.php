<?php

namespace App\DTO\Feed;

use App\Entity\Band;
use App\Entity\User;

class PostDTO
{
    public function __construct(
        public ?User $author = null,
        public ?Band $band = null,
        public ?string $content = null,
    ) {
    }
}