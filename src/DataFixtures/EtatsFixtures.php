<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatsFixtures extends Fixture
{
    private const ETATS = [
        "Créée",
        "Ouverte",
        "Clôturée",
        "Activité en cours",
        "Passé",
        "Annulée",
        "Historisée"
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::ETATS as $etatNom){
            $etat = new Etat();
            $etat->setLibelle($etatNom);
            $manager->persist($etat);
        }
        $manager->flush();
    }
}
