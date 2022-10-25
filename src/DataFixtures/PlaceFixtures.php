<?php
namespace App\DataFixtures;
use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 6; $i++) {
            $place = new Place();
            $name = $faker->word(3);
            $street = $faker->streetAddress();
            $latitude = $faker->latitude();
            $longitude  = $faker->longitude();
            $place->setName($name);
            $place->setStreet($street);
            $place->setLatitude($latitude);
            $place->setLongitude($longitude);
            $place->setCity($this->getReference(rand(0, 2).'_city'));
            
            $manager->persist($place);
            $this->addReference($i.'_place', $place);
        }
        $manager->flush();
    }

    public function getDependencies(){

        return [
            CityFixtures::class,
        ];
    }

}
