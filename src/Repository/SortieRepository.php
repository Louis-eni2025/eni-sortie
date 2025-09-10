<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function recupererSortieAHistoriser(){

        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->where('s.dateHeureDebut < :dateLimite')
            ->andWhere('e.libelle != \'Historisée\'')
            ->join('s.etat', 'e')
            ->setParameter('dateLimite', (new \DateTime())->modify('-30 days'))
            ->getQuery()
            ->getResult();
    }

    public function recupererToutesSorties(): array
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e', 'c', 'l', 'o', 'p')
            ->join('s.etat', 'e')
            ->join('s.campus', 'c')
            ->join('s.lieu', 'l')
            ->join('s.organisateur', 'o')
            ->leftJoin('s.participants', 'p')
            ->where('e.libelle not in (:etats)')
            ->setParameter('etats', ['Historisée'])
            ->orderBy('e.libelle', 'DESC')
            ->addorderBy('s.dateHeureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
