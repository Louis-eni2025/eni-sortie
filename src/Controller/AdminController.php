<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Service\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin_')]
final class AdminController extends AbstractController
{
    public function __construct(
        private UtilisateurService $utilisateurService
    )
    {}
    
    #[Route('/desactiver/{id}', name: 'desactiver')]
    public function desactiver(Utilisateur $utilisateur, EntityManagerInterface $entityManager):RedirectResponse
    {
        $utilisateur->setEstActif(!$utilisateur->isEstActif());

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $utilisateur->isEstActif() ? 'Utilisateur réactivé ✅' : 'Utilisateur désactivé ❌'
        );

        return $this->redirectToRoute('app_admin_gerer');
    }
    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(Utilisateur $utilisateur, EntityManagerInterface $entityManager):RedirectResponse
    {
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé ✅');

        return $this->redirectToRoute('app_admin_gerer');
    }




    #[Route('/gerer', name: 'gerer')]
    public function gerer (Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = $this->utilisateurService->recupererToutLesUtilisateurs();

        return $this->render('admin/list.html.twig', [
            'utilisateurs' => $utilisateur,
        ]);
    }
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès ✅');
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_admin_gerer');
        }

        return $this->render('admin/inscription.html.twig', [
            'form' => $form,
        ]);
    }

    
}
