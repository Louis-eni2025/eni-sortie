<?php

namespace App\Service;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

class SortieService
{
    public function __construct(
        private SortieRepository $sortieRepository,
        private EntityManagerInterface $entityManager,
        private EtatRepository $etatRepository,
    ){}

    public function recupererSorties():  array
    {
        return $this->sortieRepository->recupererToutesSorties();
    }

    public function recupererSortieParId(int $id):  ? Sortie
    {
        return $this->sortieRepository->find($id);
    }

    public function creerSortie(Sortie $sortie,Utilisateur $organisateur): void
    {
        $etatCree = $this->etatRepository->findOneBy(['libelle' => 'Créée']);
        if (!$etatCree) {
            throw new \RuntimeException("L'état 'Créée' n'existe pas en base !");
        }

        $sortie->setEtat($etatCree);
        $sortie->setOrganisateur($organisateur);

        $this->entityManager->persist($sortie);
        $this->entityManager->flush();
    }


}