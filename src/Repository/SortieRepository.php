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

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function recupererToutesSorties(): array
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e', 'c', 'l', 'o', 'p')
            ->join('s.etat', 'e')
            ->join('s.campus', 'c')
            ->join('s.lieu', 'l')
            ->join('s.organisateur', 'o')
            ->join('s.participants', 'p')
            ->getQuery()
            ->getResult();
    }
}
