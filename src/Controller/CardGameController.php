<?php

namespace App\Controller;

use App\Card\CardGraphic;
use App\Card\CardHand;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for the Card game in kmom02.
 */
class CardGameController extends AbstractController
{
    /**
     * @Route(
     *      "/card",
     *      name="card",
     *      methods=["GET"]
     * )
     */
    #[Route("/card", name: "card")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    /**
     * @Route(
     *      "/session",
     *      name="session"
     * )
     */
    #[Route("/session", name: "session")]
    public function session(Request $request, SessionInterface $session): Response
    {
        $card = new CardGraphic;
        $card->draw();
        $hand = new CardHand;
        $hand->add($card);
        $session->set('card', $card);
        $session->set('hand', $hand);

        $cardSession = $session->get('card');
        $handSession = $session->get('hand');
        $data = $session->all();

        return $this->render('card/session.html.twig', [
            'card' => $cardSession,
            'hand' => $handSession,
            'data' => $data
        ]);
    }
}