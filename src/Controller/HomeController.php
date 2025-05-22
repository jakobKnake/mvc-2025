<?php

namespace App\Controller;

use App\Slot\SlotMachine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
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

    #[Route("/lucky", name: "lucky")]
    public function slotMachine(): Response
    {
        $slotMachine = new SlotMachine();
        $slots = $slotMachine->spin();
        $win = $slotMachine->calculateWin($slots[0], $slots[1], $slots[2]);

        $data = [
            'slot1' => $slots[0],
            'slot2' => $slots[1],
            'slot3' => $slots[2],
            'win' => $win,
            'symbols' => $slotMachine->getSymbols()
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
    #[Route("/metrics", name: "metrics")]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }
}
