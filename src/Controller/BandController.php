<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\BandMembership;
use App\Repository\BandRepository;
use App\Service\BandService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BandController extends AbstractController
{
    public function __construct(
        private readonly BandService $service,
    )
    {
    }

    #[Route('/band', name: 'app_band_create_form', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('band/index.html.twig', [
            'controller_name' => 'BandController',
        ]);
    }

    #[Route('/bands', name: 'app_bands', methods: ['GET'])]
    public function list(BandRepository $repository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to view bands');
            return $this->redirectToRoute('app_login');
        }

        $bands = $repository->findByMember($user);
        return $this->render('band/list.html.twig', [
            'bands' => $bands,
        ]);
    }

    #[Route('/band', name: 'app_band_create', methods: ['PUT'])]
    public function create(Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $name = $request->get('name');
        $band = new Band();
        $band->setName($name);
        $em->persist($band);

        $this->service->join($this->getUser(), $band);

        $this->addFlash('notice', 'Band created successfully');
        return $this->redirectToRoute('app_bands');
    }

    #[Route('/band/{id}/delete', name: 'app_band_delete', methods: ['DELETE'])]
    public function delete(int $id, BandRepository $bandRepository, EntityManagerInterface $em): RedirectResponse
    {
        $band = $bandRepository->find($id);
        if (!$band) {
            $this->addFlash('error', 'Band not found');
            return $this->redirectToRoute('app_bands', [], 303);
        }
        $em->remove($band);
        $em->flush();

        $this->addFlash('notice', 'Band deleted successfully');
        return $this->redirectToRoute('app_bands', [], 303);
    }

    #[Route('/band/{id}', name: 'app_band_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $band = $em->getRepository(Band::class)->find($id);
        if (!$band) {
            throw $this->createNotFoundException('Band not found');
        }
        return $this->render('band/show.html.twig', [
            'band' => $band,
        ]);
    }

    #[Route('/band/join', name: 'app_band_join', methods: ['POST'])]
    public function join(Request $request, BandRepository $bandRepository, EntityManagerInterface $em): RedirectResponse
    {
        $name = $request->get('name');
        $band = $bandRepository->findOneBy(['name' => $name]);
        if (!$band) {
            $this->addFlash('error', 'Band not found');
            return $this->redirectToRoute('app_bands');
        }
        
        $this->service->join($this->getUser(), $band);

        $this->addFlash('notice', 'Joined band successfully');
        return $this->redirectToRoute('app_bands');
    }
}
