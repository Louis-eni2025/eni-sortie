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
        return $this->getEntityManager()
            ->createQuery('
            SELECT 
            s FROM App\Entity\Sortie s, 
            App\Entity\Etat e, 
            App\Entity\Campus c,
            App\Entity\Lieu l,
            App\Entity\Utilisateur u
            WHERE s.campus = c.id
            AND s.lieu = l.id
            AND s.organisateur = u.id
            AND s.etat = e.id
            
            ')
            ->getResult();
    }
}
