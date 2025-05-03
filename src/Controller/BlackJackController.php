<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for Black Jack game in kmom03.
 */
class BlackJackController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }
    #[Route("/game/doc", name: "game_doc")]
    public function gameDocumentation(): Response
    {
        return $this->render('game/doc.html.twig');
    }

}
