<?php

namespace App\Controller;

use App\Decorator\TaggedPost;
use App\Helper\PostHelper;
use App\Repository\BandMembershipRepository;
use App\Repository\BandRepository;
use App\Repository\Feed\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed', methods: ['GET'])]
    #[Route('/feed/{bandId}', name: 'app_feed_band', methods: ['GET'])]
    public function bandFeed(
        ?int $bandId,
        PostRepository $postRepository,
        BandRepository $bandRepository,
        BandMembershipRepository $bandMembershipRepository,
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to view the feed.');
            return $this->redirectToRoute('app_login');
        }

        $band = $bandId ? $bandRepository->findOneById($bandId) : null;
        $posts = $band ? $postRepository->getRecentPostsForBand($band) : $postRepository->getRecentPosts();
        $bandMemberships = $bandMembershipRepository->getByMember($user);
        $createUrl = $band ?
            $this->generateUrl('app_feed_post_create_band', ['bandId' => $band->getId()]) :
            $this->generateUrl('app_feed_post_create');

        return $this->render('feed/index.html.twig', [
            'controller_name' => 'FeedController',
            'posts' => $posts,
            'band' => $band,
            'bandMemberships' => $bandMemberships,
            'createUrl' => $createUrl,
        ]);
    }
}
