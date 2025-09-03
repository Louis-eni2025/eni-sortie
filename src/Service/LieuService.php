<?php

namespace App\Service;

use App\Entity\Lieu;
use App\Repository\LieuRepository;

class LieuService
{
    public function __construct(private LieuRepository $lieuRepository)
    {
    }

    public function recupererLieuParId(int $id) : ? Lieu
    {
        return $this->lieuRepository->find($id);
    }


}