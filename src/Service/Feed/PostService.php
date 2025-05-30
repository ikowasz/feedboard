<?php

namespace App\Service\Feed;

use App\Entity\Feed\Post;
use App\DTO\Feed\PostDTO;
use App\Entity\Feed\PostTag;
use App\Helper\TagHelper;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    public function __construct(
        private readonly TagsService $tagsService,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function createPost(PostDTO $data) 
    {
        $post = new Post();
        $this->updatePostData($post, $data);
        $tagNames = TagHelper::getTags($data->content);
        $tags = $this->tagsService->getOrCreateTags($tagNames);

        $this->entityManager->persist($post);
        $this->createPostTags($post, $tags);
        $this->entityManager->flush();

        return $post;
    }

    public function updatePostData(Post &$post, PostDTO $data) 
    {
        $post->setAuthor($data->author);
        $post->setContent($data->content);
        $post->setBand($data->band);
    }

    private function createPostTags(Post $post, array $tags): void
    {
        foreach ($tags as $tag) {
            $postTag = new PostTag();
            $postTag->setPost($post);
            $postTag->setTag($tag);
            $this->entityManager->persist($postTag);
        }
    }
}