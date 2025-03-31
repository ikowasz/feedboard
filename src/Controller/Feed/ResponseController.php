<?php

namespace App\Controller\Feed;

use App\Entity\Feed\Response;
use App\Enum\PostResponseType;
use App\Form\Feed\RespondType;
use App\Repository\Feed\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    #[Route('/feed/post/{postId}/response', name: 'app_feed_create_response', methods: ['PUT'])]
    public function createResponse(int $postId, Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $content = $request->get('content');
        $typePar = $request->get('type');
        $type = PostResponseType::tryFrom($typePar);
        $post = $postRepository->findOneById($postId);
        if (!$content || !$type || !$post) {
            $this->addFlash('error', 'Invalid response content.');
            return $this->redirectToRoute('app_feed_post', ['postId' => $postId], 303);
        }

        $response = new Response();
        $response->setContent($content);
        $response->setType($type);
        $response->setPost($post);
        $response->setAuthor($this->getUser());
        $entityManager->persist($response);
        $entityManager->flush();

        $this->addFlash('success', 'Response created successfully.');
        return $this->redirectToRoute('app_feed_post', ['postId' => $postId, '_fragment' => "responseId_{$response->getId()}"], 303);
    }
}