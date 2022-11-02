<?php

namespace App\Controller;

use App\Repository\OutingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(OutingRepository $outingRepository): Response
    {

        $outingOuverte = $outingRepository->checkOuverte('Ouverte');

        dd($outingOuverte);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
