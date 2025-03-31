<?php

namespace App\Helper;

class PostHelper
{
    public static function sanitize(string $content): string
    {
        return trim($content);
    }

    public static function linkTags(string $content, callable $generator): string
    {
        $stripped = TagHelper::strip($content);
        $matchedTags = TagHelper::matchTags($stripped);
        foreach ($matchedTags[1] as $index => $tag) {
            $original = $matchedTags[0][$index];
            $tag = TagHelper::simplify($tag);
            $text = htmlspecialchars($original);
            $link = $generator($tag, $text);
            $content = str_replace($original, $link, $content);
        }

        return $content;
    }

}