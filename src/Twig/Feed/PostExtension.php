<?php

namespace App\Twig\Feed;

use App\Helper\PostHelper;
use Twig\Extension\AbstractExtension;

class PostExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('link_tags', [$this, 'linkTags']),
        ];
    }

    public function linkTags(string $content): string
    {
        return PostHelper::linkTags($content, fn($tag, $text) => "<a class='tag-link contrast' role='link' data-tag='$tag'>$text</a>");
    }
}