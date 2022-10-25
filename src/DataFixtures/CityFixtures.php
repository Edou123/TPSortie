<?php
namespace App\DataFixtures;
use App\Entity\City;
use App\Entity\Outing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{

    // public const city = 'ville1' ;   
    // public const city2 = 'ville2';
    // public const city3 = 'ville3';


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 3; $i++) {
            $city = new City();
            $name = $faker->city();
            $cp = $faker->postcode();
            $city->setName($name);
            $city->setCp($cp);
            $manager->persist($city);
            $this->addReference($i.'_city', $city);
        }
        $manager->flush();

    }

       
  

}
