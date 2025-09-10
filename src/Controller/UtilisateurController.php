<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ImportUserType;
use App\Form\UtilisateurType;
use App\Service\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateur', name: 'utilisateur_')]
final class UtilisateurController extends AbstractController
{
    public function __construct(private UtilisateurService $utilisateurService){}

    #[Route('/{id}', name: 'profil',requirements: ['id' => '\d+'])]
    public function profil(int $id): Response
    {
        $utilisateur = $this->utilisateurService->recupererUtilisateurParId($id);

        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }

        return $this->render('utilisateur/profil.html.twig',[
            'utilisateur' => $utilisateur
        ]);
    }

    #[Route('/{id}/modifier', name: 'profil_modifier', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $utilisateur=$this->getUser();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            if ($password){
                $utilisateur->setPassword($userPasswordHasher->hashPassword($utilisateur, $password));

            }

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_profil',['id'=>$utilisateur->getId()]);
        }

        return $this->render('utilisateur/modifier_profil.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/import', name: 'import')]
    public function importUser(Request $request): Response
    {
        $form = $this->createForm(ImportUserType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $fichier = $form->get('fichier')->getData();

            if ($fichier) {
                $this->utilisateurService->importerUtilisateursDepuisCSV($fichier);
                $this->addFlash('success', 'Import réussi !');
                return $this->redirectToRoute('utilisateur_import');
            } else {
                $this->addFlash('error', 'Veuillez sélectionner un fichier CSV.');
            }
        }


        return $this->render('utilisateur/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
