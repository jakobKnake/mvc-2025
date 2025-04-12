<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route("/api", name: "api_index")]
    public function index(): Response
    {
        $apiRoutes = [
            [
                'path' => '/api/lucky/number',
                'method' => 'GET',
                'description' => 'Retunerar ett slumpmässigt nummer mellan 0-100 med ett kort meddelande.'
            ],
            [
                'path' => '/api/quote',
                'method' => 'GET',
                'description' => 'Retunerar slumpässig (bland 4) citat med dagens datum samt tidstämpel.'
            ]
        ];
        

        return $this->render('api/index.html.twig', ['routes' => $apiRoutes]);
    }
}