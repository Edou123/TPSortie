<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\Serializer;

#[AsController]
class UserByEmailController extends AbstractController
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo) {
        $this->repo = $repo;
    }

    public function __invoke(Request $request)
    {
        $email = $request->query->get('email', '');
        return $this->repo->loadUserByEmail($email);

      
        // $user = $this->repo->loadUserByEmail($username);
        // $response = new JsonResponse([
        //     'user' => $user
        // ]);
        // return $response;
    }
 
}

