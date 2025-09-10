<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ville', name: 'app_ville_')]
#[IsGranted('ROLE_ADMIN')]
final class VilleController extends AbstractController
{
    #[Route('/creer', name: 'creer')]
    #[IsGranted('ROLE_ADMIN')]
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Ville créé avec succès');
            return $this->redirectToRoute('app_ville_creer');

        }

        return $this->render('ville/creer.html.twig', [
            'form' => $form->createView(),
            'ville' => $ville,
        ]);
    }
}
