<?php

namespace App\Controller;

use App\Service\LieuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api', name: 'app_api_')]
final class ApiController extends AbstractController
{
    public function __construct(private LieuService $lieuService)
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
}
