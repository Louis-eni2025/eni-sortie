<?php

namespace App\Tests\Controller;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;
use App\Controller\ApiController;
use App\Service\SortieService;
use App\Service\LieuService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends TestCase
{
    private $lieuService;
    private $sortieService;
    private $entityManager;
    private $controller;

    protected function setUp(): void
    {
        $this->lieuService = $this->createMock(LieuService::class);
        $this->sortieService = $this->createMock(SortieService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->controller = $this->getMockBuilder(ApiController::class)
            ->setConstructorArgs([
                $this->lieuService,
                $this->sortieService,
                $this->entityManager
            ])
            ->onlyMethods(['getUser'])
            ->getMock();
    }

    public function testInscriptionSortieUtilisateurNonConnecte()
    {
        $this->controller->method('getUser')->willReturn(null);
        $this->sortieService->method('recupererSortieParId')->willReturn(new Sortie());

        $response = $this->controller->inscriptionSortie(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testInscriptionSortieSortieNonTrouvee()
    {
        $this->controller->method('getUser')->willReturn(new Utilisateur());
        $this->sortieService->method('recupererSortieParId')->willReturn(null);

        $response = $this->controller->inscriptionSortie(1);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testInscriptionSortieDejaInscrit()
    {
        $user = new Utilisateur();
        $participant = new Utilisateur();

        $sortie = new Sortie();
        $sortie->addParticipant($participant);

        $this->controller->method('getUser')->willReturn($user);
        $this->sortieService->method('recupererSortieParId')->willReturn($sortie);

        $response = $this->controller->inscriptionSortie(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('déja inscrit', $this->encodageUtf8($response));
    }

    public function testInscriptionSortieDateLimiteDepassee()
    {
        $user = new Utilisateur();
        $sortie = new Sortie();
        $sortie->setNbInscriptionsMax(20);
        $sortie->setDateLimiteInscription(new \DateTime('-1 day'));

        $this->controller->method('getUser')->willReturn($user);
        $this->sortieService->method('recupererSortieParId')->willReturn($sortie);

        $response = $this->controller->inscriptionSortie(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('date limite', $this->encodageUtf8($response));
    }

    public function testInscriptionSortieMaxParticipants()
    {
        $user = new Utilisateur();
        $user->setId(1);

        $participant1 = new Utilisateur();
        $participant1->setId(2);
        $participant2 = new Utilisateur();
        $participant2->setId(3);

        $sortie = new Sortie();
        $sortie->addParticipant($participant1);
        $sortie->addParticipant($participant2);
        $sortie->setNbInscriptionsMax(2);
        $sortie->setDateLimiteInscription(new \DateTime('+1 day'));

        $this->controller->method('getUser')->willReturn($user);
        $this->sortieService->method('recupererSortieParId')->willReturn($sortie);

        $response = $this->controller->inscriptionSortie(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('maximum de participants', $this->encodageUtf8($response));
    }

    public function testInscriptionSortieSucces()
    {
        $user = new Utilisateur();
        $sortie = new Sortie();
        $sortie->setNbInscriptionsMax(10);
        $sortie->setDateLimiteInscription(new \DateTime('+1 day'));

        $this->controller->method('getUser')->willReturn($user);
        $this->sortieService->method('recupererSortieParId')->willReturn($sortie);

        $response = $this->controller->inscriptionSortie(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('Inscription à la sortie réussie', $this->encodageUtf8($response));
    }

    private function encodageUtf8($response): bool|string
    {
        $decodedContent = json_decode($response->getContent(), true);
        $utf8Content = json_encode($decodedContent, JSON_UNESCAPED_UNICODE);
        return $utf8Content;
    }
}
