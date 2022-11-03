<?php

    namespace App\Crons;

    use App\Repository\OutingRepository;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class MajCondition extends Command {

        protected function configure () {
     

        }

        public function execute (InputInterface $input, OutputInterface $output) {

            $SortieCreer = $this->outingRepository->checkDate('Créée');
            $dateNow = new \DateTime('now');

            if($SortieCreer->getDateHourStart() < $dateNow ){

                $SortieCreer->setOutin;


            };



        }
    }