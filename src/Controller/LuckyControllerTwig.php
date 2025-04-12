<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function slotMachine(): Response
    {
        $symbols = ['cherry', 'diamond', 'bell', 'seven', 'clover', 'lemon', 'coin'];

        $slot1 = $symbols[array_rand($symbols)];
        $slot2 = $symbols[array_rand($symbols)];
        $slot3 = $symbols[array_rand($symbols)];

        $win = $this->calculateWin($slot1, $slot2, $slot3);

        $data = [
            'slot1' => $slot1,
            'slot2' => $slot2,
            'slot3' => $slot3,
            'win' => $win,
            'symbols' => $symbols
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
    /**
     * Beräkna vinst
     */
    private function calculateWin(string $slot1, string $slot2, string $slot3): array
    {
        $result = [
            'won' => false,
            'message' => 'Ingen vinst, kör igen!',
            'amount' => 0
        ];

        if ($slot1 === $slot2 && $slot2 === $slot3) {

            $result = [
                'won' => true,
                'message' => 'JACKPOT! Tre ' . $slot1 . '!',
                'amount' => 100
            ];
        }
        elseif ($slot1 === $slot2 || $slot1 === $slot3 || $slot2 === $slot3) {
            $symbol = ($slot1 === $slot2) ? $slot1: (($slot2 === $slot3) ? $slot2: $slot1);

            $result = [
                'won' => true,
                'message' => 'Vinst! Två ' . $symbol . '!',
                'amount' => 10
            ];
        }
        return $result;
    }
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
}