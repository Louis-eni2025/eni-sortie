<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurService
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager
    ){}

    public function recupererUtilisateurParId(int $id)
    {
        return $this->utilisateurRepository->find($id);
    }

    public function importerUtilisateursDepuisCSV(mixed $fichier)
    {
        $handle = fopen($fichier->getPathname(), 'r');
        if ($handle === false) {
            throw new \Exception('Impossible d\'ouvrir le fichier.');
        }


        $utilisateurs = [];
        $data = fgetcsv($handle);
        dump($data);
        exit();
        foreach($data as $line){
            dump($line);
            exit;
            dump(explode(';', $data[0]));
//            $arrayData = explode(';', $data[0]);
//            $utilisateur = new Utilisateur();
//            $utilisateur->setNom($arrayData[1]);
//            $utilisateur->setPrenom($arrayData[2]);
//            $utilisateur->setEmail($arrayData[3]);
//            $utilisateur->setTelephone($arrayData[4]);
//            $utilisateur->setPassword($this->userPasswordHasher->hashPassword($utilisateur, random_bytes(8)));

//            $this->entityManager->persist($utilisateur);
            $utilisateurs[] = $utilisateur;

        }
//        $this->entityManager->flush();
        dump($utilisateurs);
        exit;
        fclose($handle);

        return $utilisateurs;
    }


}