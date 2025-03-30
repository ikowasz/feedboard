<?php

namespace App\Controller\Feed;

use App\DTO\Feed\PostDTO;
use App\Repository\BandRepository;
use App\Repository\Feed\PostRepository;
use App\Service\Feed\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/feed/post/{postId}', name: 'app_feed_post', methods: ['GET'])]
    public function index(PostRepository $postRepository, int $postId): Response
    {
        $post = $postRepository->findOneById($postId);
        return $this->render('feed/post/index.html.twig', [
            'controller_name' => 'PostController',
            'post' => $post,
        ]);
    }

    #[Route('/feed/post', name: 'app_feed_post_create', methods: ['PUT'])]
    #[Route('/feed/post/{bandId}', name: 'app_feed_post_create_band', methods: ['PUT'])]
    public function create(Request $request, PostService $postService, BandRepository $bandRepository, ?int $bandId): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to create a post.');
            return $this->redirectToRoute('app_login');
        }

        $postData = new PostDTO();
        $postData->author = $user;
        $postData->content = $request->get('content');
        $postData->band = $bandId ? $bandRepository->getById($bandId) : null;
        $post = $postService->createPost($postData);

        $this->addFlash('success', 'Post created successfully.');
        $redirect = $postData->band ?
            $this->redirectToRoute('app_feed_band', ['bandId' => $postData->band->getId(), '_fragment' => "postId_{$post->getId()}"], 303) :
            $this->redirectToRoute('app_feed', ['_fragment' => "postId_{$post->getId()}"], 303);
        return $redirect;
    }
}
