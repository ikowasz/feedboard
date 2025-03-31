<?php

namespace App\Helper;

class TagHelper
{
    public static function strip(string $content): string
    {
        // Remove HTML tags
        $strippedContent = strip_tags($content);

        // Remove extra whitespace
        $strippedContent = preg_replace('/\s+/', ' ', $strippedContent);

        // Trim the content to a maximum length of 100 characters
        return mb_substr($strippedContent, 0, 100);
    }

    public static function matchTags(string $content): array
    {
        preg_match_all('/#([^\s]+)/', $content, $matches);
        return $matches;
    }

    public static function getTags(string $content): array
    {
        $matches = self::matchTags($content);
        $tags = array_unique($matches[1]);
        return array_map([self::class, 'simplifyTag'], $tags);
    }

    public static function simplify(string $tag): string
    {
        $tag = iconv('UTF-8', 'ASCII//TRANSLIT', $tag);
        return preg_replace('/[^a-zA-Z0-9_]/', '', $tag);
    }
}