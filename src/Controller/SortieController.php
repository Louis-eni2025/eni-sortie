<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Service\CampusService;
use App\Service\SortieService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/sortie', name: 'app_sortie_')]
final class SortieController extends AbstractController
{
    public function __construct(
        private SortieService $sortieService,
        private CampusService $campusService,
    ){}

    #[Route('', name: 'list')]
    public function list(): Response
    {
        $sorties = $this->sortieService->recupererSorties();
        $campus = $this->campusService->recupererCampus();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'campusList' => $campus
        ]);
    }
    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(int $id): Response
    {

        $sortie = $this->sortieService->recupererSortieParId($id);

        if (!$sortie) {
            throw $this->createNotFoundException('Sortie introuvable');
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);

    }
    #[Route('/{id}/modifier', name: 'modifier', requirements: ['id' => '\d+'])]
    public function modifier(EntityManagerInterface $entityManager,Request $request,int $id,Sortie $sortie): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_detail',['id'=>$id]);
        }

        return $this->render('sortie/modifier.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'fromParameter'=>'app_sortie_modifier',
        ]);
    }
    #[Route('/{id}/supprimer', name: 'supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(int $id,EntityManagerInterface $entityManager): Response
    {
       $sortie = $this->sortieService->recupererSortieParId($id);
       if (!$sortie) {
           throw $this->createNotFoundException('Sortie introuvable');
       }
       $entityManager->remove($sortie);
       $entityManager->flush();
       return $this->redirectToRoute('app_sortie_list');
    }





}
