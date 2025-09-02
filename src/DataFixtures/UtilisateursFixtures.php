<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\FixtureHelperTrait;
use App\Entity\Campus;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateursFixtures extends Fixture implements DependentFixtureInterface
{

    use FixtureHelperTrait;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {

    }
    public function load(ObjectManager $manager): void
    {
       $this->ajouterUtilisateurs($manager);
    }

    public function ajouterUtilisateurs(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $utilisateur = new Utilisateur();

        $utilisateur->setNom($faker->firstName());
        $utilisateur->setPrenom($faker->lastName());
        $utilisateur->setEmail('organisateur@mail.fr');
        $utilisateur->setPassword($this->userPasswordHasher->hashPassword($utilisateur, 'organisateur'));
        $utilisateur->setRoles(['ROLE_USER']);
        $utilisateur->setTelephone($faker->phoneNumber());
        $utilisateur->setEstActif(true);
        $utilisateur->setCampus($faker->randomElement($this->getAllReferencesByPrefix($this->referenceRepository, "campus_")));
        $this->addReference('user_organisateur', $utilisateur);
        $manager->persist($utilisateur);

        $participant = new Utilisateur();

        $participant->setNom($faker->firstName());
        $participant->setPrenom($faker->lastName());
        $participant->setEmail('participant@mail.fr');
        $participant->setPassword($this->userPasswordHasher->hashPassword($participant, 'participant'));
        $participant->setRoles(['ROLE_USER']);
        $participant->setTelephone($faker->phoneNumber());
        $participant->setEstActif(true);
        $participant->setCampus($faker->randomElement($this->getAllReferencesByPrefix($this->referenceRepository, "campus_")));
        $this->addReference('user_participant', $participant);
        $manager->persist($participant);

        $admin = new Utilisateur();

        $admin->setNom($faker->firstName());
        $admin->setPrenom($faker->lastName());
        $admin->setEmail('admin@mail.fr');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $admin->setTelephone($faker->phoneNumber());
        $admin->setEstActif(true);
        $admin->setCampus($faker->randomElement($this->getAllReferencesByPrefix($this->referenceRepository, "campus_")));
        $manager->persist($admin);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CampusFixtures::class
        ];
    }
}
