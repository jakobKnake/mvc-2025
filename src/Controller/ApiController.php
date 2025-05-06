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
            ],
            [
                'path' => '/api/deck',
                'method' => 'GET',
                'description' => 'Retunerar en kortlek sorterad på färg och värde.'
            ],
            [
                'path' => '/api/deck/shuffle',
                'method' => 'POST',
                'description' => 'Blandar kortleken.'
            ],
            [
                'path' => '/api/deck/draw',
                'method' => 'POST',
                'description' => 'Drar ett kort från kortleken, visar upp det dragna kortet samt antal kort kvar i leken.'
            ],
            [
                'path' => '/api/deck/draw/:number',
                'method' => 'POST',
                'description' => 'Drar specifikt antal kort från kortleken, visar upp dem samt antal kort kvar i leken.'
            ],
            [
                'path' => '/api/game',
                'method' => 'GET',
                'description' => 'Visar aktuella ställningen från Black Jack spelet.'
            ]
        ];


        return $this->render('api/index.html.twig', ['routes' => $apiRoutes]);
    }
}
