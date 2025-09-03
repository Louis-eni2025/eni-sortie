<?php

namespace App\Service;

use App\Entity\Sortie;
use App\Repository\SortieRepository;

class SortieService
{
    public function __construct(private SortieRepository $sortieRepository){}

    public function recupererSorties():  array
    {
        return $this->sortieRepository->findAll();
    }

    public function recupererSortieParId(int $id):  ? Sortie
    {
        return $this->sortieRepository->find($id);
    }
}