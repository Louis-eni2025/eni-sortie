<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VillesFixtures extends Fixture
{
    private const VILLES = [
        "Rennes" => "35000",
        "Nantes" => "44000",
        "Paris" => "75000",
    ];

    public function load(ObjectManager $manager): void
    {
        foreach(self::VILLES as $nomVille => $cp){
            $ville = new Ville();
            $ville->setNom($nomVille);
            $ville->setCodePostal($cp);
            $manager->persist($ville);

        }
        $manager->flush();
    }

}
