<?php

namespace App\Controller;

use ApiPlatform\OpenApi\Model\Response;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Serializer;

#[AsController]
class NewUserController extends AbstractController
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo) {
        $this->repo = $repo;
    }

    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user=$request->get('data');

        $pwd = $user->getPassword();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $pwd
        );
        

        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return 1;
    }
 
}

