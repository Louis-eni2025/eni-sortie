<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\LieuType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/campus', name: 'app_campus_')]
final class CampusController extends AbstractController
{
    #[Route('/creer', name: 'creer')]
    #[IsGranted('ROLE_ADMIN')]
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Campus créé avec succès');
            return $this->redirectToRoute('app_campus_creer');

        }

        return $this->render('campus/creer.html.twig', [
            'form' => $form->createView(),
            'campus' => $campus,
        ]);
    }
}
