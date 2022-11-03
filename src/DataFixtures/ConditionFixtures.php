<?php
namespace App\DataFixtures;

use App\Entity\Condition;
use App\Entity\Outing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ConditionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create('fr_FR');

        $etat = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'];

        for ($j = 0; $j < 6; $j++){
            $condition = new Condition();
            
            $libelle = $etat[$j];
            $condition->setLibelle($libelle);

            $manager->persist($condition);
            $this->addReference($j.'_condition', $condition);
        }
        $manager->flush();
    }

    
    public function getDependencies(){

        return [
            PlaceFixtures::class,
        ];
    }


}
