<?php

namespace App\Tests\Service;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Service\SortieService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SortieServiceTest extends TestCase
{
    private $sortieRepository;
    private $etatRepository;
    private $em;

    private $service;

    protected function setUp(): void{
        $this->sortieRepository = $this->createMock(SortieRepository::class);
        $this->etatRepository = $this->createMock(EtatRepository::class);
        $this->em = $this->createMock(EntityManagerInterface::class);

//        $this->service = $this->getMockBuilder(SortieService::class)
//            ->setConstructorArgs([
//                $this->sortieRepository,
//                $this->em,
//                $this->etatRepository
//            ])
//            ->getMock();
        $this->service = new SortieService(
            $this->sortieRepository,
            $this->em,
            $this->etatRepository
        );


    }
    public function testCreerSortie()
    {
        $sortie = new Sortie();
        $organisateur = new Utilisateur();
        $etatCree = new Etat();
        $etatCree->setLibelle('Créée');
        $sortie->setOrganisateur($organisateur);
        $sortie->setEtat($etatCree);


        $this->etatRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['libelle' => 'Créée'])
            ->willReturn($etatCree);

        $this->em->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($sortie));
        $this->em->expects($this->once())
            ->method('flush');


        $this->service->creerSortie($sortie, $organisateur);

        $this->assertSame($etatCree, $sortie->getEtat());
        $this->assertSame($organisateur, $sortie->getOrganisateur());
    }

    public function testRecupererSorties()
    {

    }

    public function testRecupererSortieParId()
    {

    }
}
