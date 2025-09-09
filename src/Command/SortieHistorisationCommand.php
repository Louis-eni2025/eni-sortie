<?php

namespace App\Command;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// Commande a lancer tous les jours à minuit
// Cron guru: 0 0 * * * php bin/console sortie:historisation
#[AsCommand(
    name: 'sortie:historisation',
    description: 'Historise les sorties qui ont eu lieu il y a plus de 30 jours',
)]
class SortieHistorisationCommand extends Command
{
    public function __construct
    (
        private SortieRepository $sortieRepository,
        private EtatRepository $etatRepository,
        private EntityManagerInterface $manager
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Début de la commande d\'historisation des sorties');

        $sorties = $this->sortieRepository->recupererSortieAHistoriser();
        $io->note('Nombre de sorties à historiser : '.count($sorties));

        $etat = $this->etatRepository->findOneBy(['libelle' => 'Historisée']);

        if($etat === null){
            $io->error('Etat "Historisée" non trouvé en base de données.');
            return Command::FAILURE;
        }
        if(empty($sorties)){
            $io->success('Aucune sortie à historiser. Fin de la commande.');
            return Command::SUCCESS;
        }

        foreach ($sorties as $sortie) {
            $sortie->setEtat($etat);
            $io->note(sprintf('Sortie n°%s | Date de début: %s | Etat: %s', $sortie->getId(), $sortie->getDateHeureDebut()->format('d/m/Y h:i:s') ,$etat->getLibelle()));
            $this->manager->persist($sortie);
        }
        $this->manager->flush();

        $io->success('Commande terminée.');

        return Command::SUCCESS;
    }
}
