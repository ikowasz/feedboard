<?php

namespace App\Service\Feed;

use App\Entity\Feed\Tag;
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

    public static function getTagsFromContent(string $content): array
    {
        preg_match_all('/#([^\s]+)/', $content, $matches);
        $tags = array_unique($matches[1]);
        return array_map([self::class, 'simplifyTag'], $tags);
    }

    public static function simplifyTag(string $tag): string
    {
        $tag = iconv('UTF-8', 'ASCII//TRANSLIT', $tag);
        return preg_replace('/[^a-zA-Z0-9_]/', '', $tag);
    }

    public function getOrCreateTagsFromContent(string $content): array
    {
        $tags = self::getTagsFromContent($content);
        return $this->getOrCreateTags($tags);
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