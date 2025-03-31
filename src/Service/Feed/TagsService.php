<?php

namespace App\Service\Feed;

use App\Entity\Feed\Tag;
use App\Helper\TagHelper;
use App\Repository\Feed\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class TagsService
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function getOrCreateTags(array $tags): array
    {
        $existingTags = $this->tagRepository->findBy(['name' => $tags]);
        $existingTagNames = array_map(fn($tag) => $tag->getName(), $existingTags);

        $newTags = array_diff($tags, $existingTagNames);
        foreach ($newTags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            $existingTags[] = $tag;
        }

        return $existingTags;
    }
}