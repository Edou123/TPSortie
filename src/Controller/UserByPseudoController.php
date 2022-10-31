<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\Serializer;

#[AsController]
class UserByPseudoController extends AbstractController
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo) {
        $this->repo = $repo;
    }

    public function __invoke(string $pseudo)
    {
        $user = $this->repo->loadUserByPseudo($pseudo);
        $response = new JsonResponse([
            'user' => $user
        ]);
        return $response;
    }
 
}

