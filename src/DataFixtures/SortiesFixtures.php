<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\FixtureHelperTrait;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SortiesFixtures extends Fixture implements DependentFixtureInterface
{

    use FixtureHelperTrait;

    public function __construct(){}
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 20; $i++){
            $sortie = new Sortie();
            $dateDebut = $faker->dateTimeBetween('-5 months', '+5 months');

            //La date limite d'inscription sera toujours a date event - 1 semaine
            $dateLimiteInscription = (clone $dateDebut)->modify('-1 weeks');

            $sortie->setNom('Sortie');
            $sortie->setDateHeureDebut($dateDebut);

            // on fait x 10 pour avoir un chiffre rond
            $sortie->setDuree(($faker->numberBetween(3,12)*10));

            $sortie->setDateLimiteInscription($dateLimiteInscription);
            $sortie->setNbInscriptionsMax(100);
            $sortie->setInfosSortie('Informations de la sortie');

            $sortie->setCampus($faker->randomElement($this->getAllReferencesByPrefix($this->referenceRepository, "campus_")));
            $sortie->setOrganisateur($this->getReference('user_organisateur', Utilisateur::class));
            $sortie->addParticipant($this->getReference('user_participant', Utilisateur::class));
            $sortie->setLieu($faker->randomElement($this->getAllReferencesByPrefix($this->referenceRepository, "lieu_")));

            $manager->persist($sortie);
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LieuxFixtures::class,
            CampusFixtures::class,
            UtilisateursFixtures::class,
            EtatsFixtures::class,
        ];
    }
}
