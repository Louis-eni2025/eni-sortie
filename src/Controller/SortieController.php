<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
use App\Form\CreerSortieType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Service\CampusService;
use App\Service\SortieService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/sortie', name: 'app_sortie_')]
final class SortieController extends AbstractController
{
    public function __construct(
        private SortieService $sortieService,
        private CampusService $campusService,
        private EtatRepository $etatRepository
    ){}

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $sorties = $this->sortieService->recupererSorties();
        $campus = $this->campusService->recupererCampus();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'campusList' => $campus
        ]);
    }

    #[Route('/creer', name: 'creer')]
    public function creer(Request $request,SortieService $sortieService): Response
    {
        $sortie = new Sortie();
        $utilisateur = $this->getUser();
        if ($utilisateur && $utilisateur->getCampus()) {
            $sortie->setCampus($utilisateur->getCampus());
        }
        $form = $this->createForm(CreerSortieType::class, $sortie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $action = $request->request->get('action');

            $sortieService->creerSortie($sortie, $utilisateur, $action);
            return $this->redirectToRoute('app_sortie_list');
        }
        return $this->render('sortie/creer.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'fromParameter'=>'app_sortie_creer',

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
    #[IsGranted('SORTIE_EDIT', 'sortie')]
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
    #[IsGranted('SORTIE_EDIT', 'sortie')]
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

    #[Route('/{id}/annuler',name: 'annuler', requirements: ['id' => '\d+'])]
    #[IsGranted('SORTIE_EDIT', 'sortie')]
    public function annuler(int $id,EntityManagerInterface $entityManager,Request $request,Sortie $sortie): Response
    {
        $sortie = $this->sortieService->recupererSortieParId($id);

        if($sortie->getDateHeureDebut() < new \DateTime()){
            $this->addFlash('error','Vous ne pouvez plus annuler cette sortie');
            return $this->redirectToRoute('app_sortie_detail',['id'=>$id]);
        }

        $form = $this->createForm(AnnulerSortieType::class, $sortie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $motif = $form->get('motif')->getData();
            $infoSortie = $sortie->getInfosSortie() ??'';
            $sortie->setInfosSortie($infoSortie . "\n[Motif d'annulation : " . $motif . "]");

            $etat = $this->etatRepository->findOneBy(['libelle' => 'Annulée']);
            if ($etat === null) {
                $this->addFlash('error', 'Etat "Annulée" non trouvé en base de données.');
                return $this->redirectToRoute('app_sortie_detail', ['id' => $id]);
            } else {
                $sortie->setEtat($etat);
            }

            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_sortie_detail',['id'=>$id]);
        }

        return $this->render('sortie/annuler.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }



}
