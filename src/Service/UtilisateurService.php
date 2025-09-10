<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\CampusRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurService
{
    public function __construct(
        private UtilisateurRepository       $utilisateurRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface      $entityManager,
        private readonly CampusRepository $campusRepository
    ){}

    public function recupererUtilisateurParId(int $id)
    {
        return $this->utilisateurRepository->find($id);
    }

    public function importerUtilisateursDepuisCSV(mixed $fichier)
    {
        $handle = fopen($fichier->getPathname(), 'r');

        $utilisateurs = [];
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($data[1]);
            $utilisateur->setPrenom($data[2]);
            $utilisateur->setEmail($data[3]);
            $utilisateur->setTelephone($data[4]);
            $utilisateur->setEstActif(true);

            $utilisateur->setCampus($this->campusRepository->findOneBy(['nom' => 'Rennes']));

            $randomPassword = bin2hex(random_bytes(4));
            $utilisateur->setPassword($this->userPasswordHasher->hashPassword($utilisateur, $randomPassword));

            $this->entityManager->persist($utilisateur);
        }
        $this->entityManager->flush();

        fclose($handle);

        return $utilisateurs;
    }


}