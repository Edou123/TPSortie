<?php

    namespace App\Crons;

use App\Entity\Condition;
use App\Repository\ConditionRepository;
use App\Repository\OutingRepository;
    use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Serializer;

    #[AsCommand(name: "app:checkCondition")]
    class MajCondition extends Command {

        public function __construct(OutingRepository $outingRepository, ConditionRepository $conditionRepository, ManagerRegistry $doctrine)
        {
            $this->outingRepository = $outingRepository;
            $this->conditionRepository = $conditionRepository;
            $this->doctrine = $doctrine;

            parent::__construct();
        }
    

        protected function configure () {
    
        }

        public function execute (InputInterface $input, OutputInterface $output):int {
            $io = new SymfonyStyle($input, $output);

            while(true){
            
                $entityManager = $this->doctrine->getManager();
                $dateNow = new \DateTime('now');
                $ConditionOuverte = $this->conditionRepository->findBy(array('libelle'=>"Ouverte"));
                $ConditionCloture = $this->conditionRepository->findBy(array('libelle'=>"Clôturée"));
                $ConditionEnCours = $this->conditionRepository->findBy(array('libelle'=>"Activité en cours"));
                $ConditionPasse = $this->conditionRepository->findBy(array('libelle'=>"Passée"));

                $SortiesOuvertes = $this->outingRepository->recoverOutingFromCondition('Ouverte');
                $nbSortiesOuvertes = count($SortiesOuvertes);
                if($nbSortiesOuvertes >= 1){
                    //Ouvertes
                    for ($i=0; $i<$nbSortiesOuvertes; $i++){
                        $dateCloture = $SortiesOuvertes[$i]->getDateLimitRegistration();
                        $interval = $dateNow->diff($dateCloture);
                        if($interval->format('%a') >= 0 ){
                            $SortiesOuvertes[$i]->setOutingCondition($ConditionCloture[0]);
                            $entityManager->persist($SortiesOuvertes[$i]);
                            $entityManager->flush();
                        }
                    }
                }

                $SortiesClotures = $this->outingRepository->recoverOutingFromCondition('Clôturée');
                $nbSortiesClotures = count($SortiesClotures);
                if($nbSortiesClotures >= 1){
                    //Clotures
                    for ($i=0; $i<$nbSortiesClotures; $i++){
                        $dateStart = $SortiesClotures[$i]->getDateHourStart();
                        $interval = $dateNow->diff($dateStart);
                        if($interval->format('%R%a') >= 0 ){
                            $SortiesClotures[$i]->setOutingCondition($ConditionEnCours[0]);
                            $entityManager->persist($SortiesClotures[$i]);
                            $entityManager->flush();
                        }
                    }
                }

                $SortiesClotures = $this->outingRepository->recoverOutingFromCondition('Clôturée');
                $nbSortiesClotures = count($SortiesClotures);
                if($nbSortiesClotures >= 1){
                    //En Cours
                    for ($i=0; $i<$nbSortiesClotures; $i++){
                        $dateStart = $SortiesClotures[$i]->getDateHourStart();
                        $interval = $dateNow->diff($dateStart);
                        if($interval->format('%R%a') >= 0 ){
                            $SortiesClotures[$i]->setOutingCondition($ConditionEnCours[0]);
                            $entityManager->persist($SortiesClotures[$i]);
                            $entityManager->flush();
                        }
                    }
                }

                $SortiesEnCours = $this->outingRepository->recoverOutingFromCondition('Activité en cours');
                $nbSortiesEnCours = count($SortiesEnCours);
                if($nbSortiesEnCours >= 1){
                    // Passée
                    for ($i=0; $i<$nbSortiesEnCours; $i++){
                        $dateStart = $SortiesEnCours[$i]->getDateHourStart();
                        $dateFin = $dateStart->modify('+'.$SortiesEnCours[$i]->getDuration().' hour');
                        $interval = $dateNow->diff($dateFin);
                        if($interval->format('%R%a') >= 0 ){
                            $SortiesEnCours[$i]->setOutingCondition($ConditionPasse[0]);
                            $entityManager->persist($SortiesEnCours[$i]);
                            $entityManager->flush();
                        }
                    }
                }

                $SortiesCreerEtOuvertes = $this->outingRepository->recoverOutingFromCondition('Créée');
                $SortiesCreerEtOuvertes+=$this->outingRepository->recoverOutingFromCondition('Ouverte');
                $nbSortiesCreerEtOuvertes = count($SortiesCreerEtOuvertes);
                if($nbSortiesCreerEtOuvertes >= 1){
                    //Créée
                    for ($i=0; $i<$nbSortiesCreerEtOuvertes; $i++){
                        $dateFin = ($SortiesCreerEtOuvertes[$i]->getDateHourStart())->modify('+'.$SortiesCreerEtOuvertes[$i]->getDuration().' hour');
                        $interval = $dateNow->diff($dateFin);
                        if($interval->format('%a') >= 0 ){
                            $SortiesCreerEtOuvertes[$i]->setOutingCondition($ConditionPasse[0]);
                            $entityManager->persist($SortiesCreerEtOuvertes[$i]);
                            $entityManager->flush();
                        }
                    }
                }
                
                $ok= new DateTime();
                $io->success(sprintf( $ok->format('Y-m-d H:i:s')));
                sleep(300);


            }

            return Command::SUCCESS;
        }

    }