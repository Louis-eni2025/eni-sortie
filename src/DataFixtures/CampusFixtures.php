<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    private const CAMPUS = [
        "Paris",
        "Rennes",
        "Nantes"
    ];
    public function load(ObjectManager $manager): void
    {
        foreach(self::CAMPUS as $nomCampus){
            $campus = new Campus();
            $campus->setNom($nomCampus);
            $manager->persist($campus);
        }

        $manager->flush();
    }

}
