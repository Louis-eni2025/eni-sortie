<?php

namespace App\Service;

use App\Repository\UtilisateurRepository;

class UtilisateurService
{
    public function __construct(private UtilisateurRepository $utilisateurRepository){}

    public function recupererUtilisateurParId(int $id)
    {
        return $this->utilisateurRepository->find($id);
    }

    public function recupererToutLesUtilisateurs()
    {
       return $this->utilisateurRepository->findAll();
    }


}