<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Sortie;
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
        "Annulée"
    ];
    public function __construct(){}
    public function load(ObjectManager $manager): void
    {
        $i=0;
        foreach (self::ETATS as $etatNom){
            $etat = new Etat();
            $etat->setLibelle($etatNom);
            $manager->persist($etat);

            $this->addReference('etat_'.$i++, $etat);
        }

        $manager->flush();
    }
}
