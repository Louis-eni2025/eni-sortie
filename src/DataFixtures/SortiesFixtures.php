<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SortiesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $campusList =  $manager->getRepository(Campus::class)->findAll();
        $organisateur = $manager->getRepository(Utilisateur::class)->findOneBy(['email'=>"organisateur@mail.fr"]);
        $participant = $manager->getRepository(Utilisateur::class)->findOneBy(['email'=>"participant@mail.fr"]);
        $lieux = $manager->getRepository(Lieu::class)->findAll();
        $etats = $manager->getRepository(Etat::class)->findAll();

        for($i = 0; $i < 20; $i++){
            $sortie = new Sortie();
            $dateDebut = $faker->dateTimeBetween('-5 months', '+5 months');

            //La date limite d'inscription sera toujours a date event - 1 semaine
            $dateLimiteInscription = (clone $dateDebut)->modify('-1 weeks');

            $sortie->setNom("Sortie au ".$faker->jobTitle()." ".$faker->colorName());
            $sortie->setDateHeureDebut($dateDebut);

            // on fait x 10 pour avoir un chiffre rond
            $sortie->setDuree(($faker->numberBetween(3,12)*10));

            $sortie->setDateLimiteInscription($dateLimiteInscription);
            $sortie->setNbInscriptionsMax(100);
            $sortie->setInfosSortie('Informations de la sortie');

            $sortie->setCampus($faker->randomElement($campusList));
            $sortie->setOrganisateur($organisateur);
            $sortie->addParticipant($participant);
            $sortie->setLieu($faker->randomElement($lieux));
            $sortie->setEtat($faker->randomElement($etats));

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
