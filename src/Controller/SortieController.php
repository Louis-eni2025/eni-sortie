<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Service\CampusService;
use App\Service\SortieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SortieController extends AbstractController
{
    public function __construct(
        private SortieService $sortieService,
        private CampusService $campusService,
    ){}

    #[Route('/', name: 'app_sortie')]
    public function index(): Response
    {
        $sorties = $this->sortieService->recupererSorties();
        $campus = $this->campusService->recupererCampus();
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'campusList' => $campus
        ]);
    }
}
