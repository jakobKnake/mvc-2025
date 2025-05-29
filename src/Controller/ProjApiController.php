<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\User;
use App\Repository\HistoryRepository;
use App\Repository\UserRepository;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Exception;

class ProjApiController extends AbstractController
{

    #[Route("/proj/api", name: "api_proj_index")]
    public function ApiIndex(): Response
    {
        $apiRoutes = [
            [
                'path' => '/api/lucky/number',
                'name' => 'api_lucky_num',
                'method' => 'GET',
                'description' => 'Retunerar ett slumpmässigt nummer mellan 0-100 med ett kort meddelande.'
            ],
            [
                'path' => '/api/deck/draw',
                'name' => 'api_draw_deck',
                'method' => 'POST',
                'description' => 'Drar ett kort från kortleken, visar upp det dragna kortet samt antal kort kvar i leken.'
            ],
            [
                'path' => '/api/deck/draw/:number',
                'name' => 'api_draw_multi',
                'method' => 'POST',
                'description' => 'Drar specifikt antal kort från kortleken, visar upp dem samt antal kort kvar i leken.'
            ],

        ];
    }

}
