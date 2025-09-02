<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\FixtureHelperTrait;
use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LieuxFixtures extends Fixture implements DependentFixtureInterface
{
    use FixtureHelperTrait;

    private const LIEUX = [
        "Rennes" => "35000",
        "Nantes" => "44000",
        "Paris" => "75000",
    ];

    public function __construct(){}
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $villes = $manager->getRepository(Ville::class)->findAll();

        for($i=0;$i<20;$i++){
            $lieu =  new Lieu();
            $lieu->setNom($faker->company);
            $lieu->setRue($faker->streetAddress);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);
            $lieu->setVille($faker->randomElement($villes));

            $manager->persist($lieu);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VillesFixtures::class
        ];
    }
}
