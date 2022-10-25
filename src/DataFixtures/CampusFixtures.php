<?php
namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CampusFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 8; $i++) {
            $campus = new Campus();
            $name = $faker->word(2);
            $campus->setName($name);
            $manager->persist($campus);
            $this->addReference($i.'_campus', $campus);
        }
        $manager->flush();

    }

}
