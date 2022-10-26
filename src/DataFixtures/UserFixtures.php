<?php
namespace App\DataFixtures;
use App\Entity\Outing;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($j = 0; $j < 10; $j++){
            
            $user = new User();
            $pseudo = $faker->userName();

            $mf = rand(0,1);
            if($mf === 0 ){
                $name = $faker->lastName();
                $firstname = $faker->firstName('male');
            }else{
                $name = $faker->lastName();
                $firstname = $faker->firstName('female');
            }
            $tel = $faker->mobileNumber();
            $email = $faker->email();
            $mdp = '$2y$13$X/6dw.tjvgkwGKjzrkU16O/Uox6TPmpE6Ti2DlGhKG5PiSj5KiMpK';
            if($j===0){
                $admin = true;
            }else{
                $admin = false;
            }
            $actif = true;

            $user->setPseudo($pseudo);
            $user->setName($name);
            $user->setFirstname($firstname);
            $user->setPhone($tel);
            $user->setEmail($email);
            $user->setPassword($mdp);
            $user->setAdministrator($admin);
            if($admin===true){
                $user->setRoles(['ROLE_ADMINISTRATEUR']);
            }else{
                $user->setRoles(['ROLE_USER']);
            }
            $user->setActif($actif);
            $user->setCampus($this->getReference(rand(0,7).'_campus'));

            $manager->persist($user);
            $this->addReference($j.'_user', $user);
        }
        $manager->flush();
    }

    
    public function getDependencies(){

        return [
            // OutingFixtures::class,
            CampusFixtures::class,
        ];
    }


}
