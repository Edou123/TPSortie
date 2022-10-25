<?php
namespace App\DataFixtures;
use App\Entity\Outing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class OutingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($j = 0; $j < 5; $j++){
            $outing = new Outing();
            $name = $faker->word(5);
            if($j===0){
                $dateHourStart = $faker->datetime('now');
            }else{
                $dateHourStart = $faker->datetime();
            }
            $duration = $faker->numberBetween(1, 10);
            $dateLimitRegistration = date('Y-m-d H:i:s', strtotime('-2 day', strtotime($dateHourStart->format('Y-m-d H:i:s'))));
            $nbRegistrationMax = $faker->randomNumber(2);
            $infosOuting = $faker->sentence();
            
            $outing->setName($name);
            $outing->setDateHourStart($dateHourStart);
            $outing->setDuration($duration);
            $outing->setDateLimitRegistration(\DateTime::createFromFormat('Y-m-d H:i:s', $dateLimitRegistration));
            $outing->setNbRegistrationMax($nbRegistrationMax);
            $outing->setInfosOuting($infosOuting);
            $outing->setPlace($this->getReference(rand(0, 4).'_place'));
            $outing->setCampus($this->getReference(rand(0, 7).'_campus'));
            $outing->setOutingCondition($this->getReference('0'.'_condition'));
            $outing->setOrganizer($this->getReference(rand(0,9).'_user'));
           
            $nbReg = rand(0,9);
            for ($k = 0; $k < $nbReg; $k++){
                $user = $this->getReference(rand(0,9).'_user');
                $outing->addRegistered($user);
            }        

            $manager->persist($outing);
            $this->addReference($j.'_outing', $outing);
        }
        $manager->flush();

    }
    
    public function getDependencies(){

        return [
            PlaceFixtures::class,
            CampusFixtures::class,
            UserFixtures::class,
            ConditionFixtures::class,
        ];
    }


}
