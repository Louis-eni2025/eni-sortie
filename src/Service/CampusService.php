<?php

namespace App\Service;

use App\Repository\CampusRepository;
use App\Repository\SortieRepository;

class CampusService
{
    public function __construct(private CampusRepository $campusRepository){}

    public function recupererCampus():  array
    {
        return $this->campusRepository->findAll();
    }
}