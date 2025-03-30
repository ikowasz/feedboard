<?php

namespace App\Controller;

use App\Repository\BandRepository;
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
            'band' => null,
        ]);
    }

    #[Route('/feed/{bandId}', name: 'app_feed_band', methods: ['GET'])]
    public function bandFeed(PostRepository $postRepository, BandRepository $bandRepository, int $bandId): Response
    {
        $band = $bandRepository->findOneById($bandId);
        $posts = $postRepository->getRecentPostsForBand($band);

        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController',
            'posts' => $posts,
            'band' => $band,
        ]);
    }
}
