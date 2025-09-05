<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieu', name: 'app_lieu_')]
final class LieuController extends AbstractController
{
    #[Route('/creer', name: 'creer')]
    public function creer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $fromParameter = $request->get("from");
        $idSortie =$request->get("id");
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);
        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();
            if ($fromParameter) {
                return $this->redirectToRoute($fromParameter, [
                    'id' => $idSortie
                ]);
            } else {
                return $this->redirectToRoute('app_lieu_creer');
            }

        }

        return $this->render('lieu/creer.html.twig', [
            'lieuForm' => $lieuForm,
            'lieu' => $lieu,
        ]);
    }
}
