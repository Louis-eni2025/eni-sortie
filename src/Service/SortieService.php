<?php

namespace App\Service;

use App\Repository\SortieRepository;

class SortieService
{
    public function __construct(private SortieRepository $sortieRepository){}

    public function recupererSorties():  array
    {
        return $this->sortieRepository->findAll();
    }
}