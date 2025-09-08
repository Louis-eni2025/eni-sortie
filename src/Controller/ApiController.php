<?php

namespace App\Controller;

use App\Service\LieuService;
use App\Service\SortieService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'app_api_')]
final class ApiController extends AbstractController
{
    public function __construct(
        private readonly LieuService   $lieuService,
        private readonly SortieService $sortieService,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }
    #[Route('/lieu/{id}', name: 'lieu', methods: ['GET'])]
    public function recupererLieuParId(int $id): Response
    {
        $lieu = $this->lieuService->recupererLieuParId($id);

        if (!$lieu) {
            $retour = [
                'message' => 'Lieu non trouvé',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_NOT_FOUND);
        }

        return $this->json($lieu, Response::HTTP_OK, [], ['groups' => ['lieu']]);
    }

    #[Route('/inscription/{id}', name: 'inscription_sortie', methods: ['GET'])]
    public function inscriptionSortie(int $id): Response
    {
        $sortie = $this->sortieService->recupererSortieParId($id);

        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            $retour = [
                'message' => 'Accès non autorisé',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if (!$sortie) {
            $retour = [
                'message' => 'Sortie non trouvé',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_NOT_FOUND);
        }

        foreach ($sortie->getParticipants() as $participant) {
            if($participant->getId() === $utilisateur->getId()){
                $retour = [
                    'message' => 'Vous êtes déja inscrit à cette sortie',
                    'erreur' => true
                ];
                return new JsonResponse($retour, Response::HTTP_OK);
            }
        }

        if($sortie->getDateLimiteInscription() > new DateTime()) {
            $retour = [
                'message' => 'La date limite d\'inscription est dépassée',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_OK);
        }

        if($sortie->getNbInscriptionsMax() <= count($sortie->getParticipants())) {
            $retour = [
                'message' => 'La nombre maximum de participants est atteint',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_OK);
        }

        $sortie->addParticipant($utilisateur);
        $this->entityManager->persist($sortie);
        $this->entityManager->flush();

        $retour = [
            'message' => 'Inscription à la sortie réussie',
            'erreur' => false
        ];

        return $this->json($retour, Response::HTTP_OK);
    }

    #[Route('/desinscription/{id}', name: 'desinscription_sortie', methods: ['GET'])]
    public function desinscriptionSortie(int $id): Response
    {

        $sortie = $this->sortieService->recupererSortieParId($id);

        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            $retour = [
                'message' => 'Accès non autorisé',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if (!$sortie) {
            $retour = [
                'message' => 'Sortie non trouvé',
                'erreur' => true
            ];
            return new JsonResponse($retour, Response::HTTP_NOT_FOUND);
        }

        foreach ($sortie->getParticipants() as $participant) {
            dump($participant->getId());
            dump($utilisateur->getId());
            if($participant->getId() === $utilisateur->getId()){

                $sortie->removeParticipant($utilisateur);
                $this->entityManager->persist($sortie);
                $this->entityManager->flush();

                $retour = [
                    'message' => 'Vous êtes bien désinscrit de cette sortie',
                    'erreur' => false
                ];
                return new JsonResponse($retour, Response::HTTP_OK);
            } else {
                $retour = [
                    'message' => 'Vous n\'êtes pas inscrit à cette sortie',
                    'erreur' => false
                ];
            }

        }
        return new JsonResponse($retour, Response::HTTP_OK);

        return new JsonResponse(['message' => 'Fonctionnalité non implémentée'], Response::HTTP_NOT_IMPLEMENTED);
    }
}
