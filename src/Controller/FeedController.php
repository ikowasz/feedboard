<?php

namespace App\Controller;

use App\Repository\Feed\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->getRecentPosts();

        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController',
            'posts' => $posts,
        ]);
    }
}
