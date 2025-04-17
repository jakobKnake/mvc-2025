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
     *      "/card/deck",
     *      name="deck",
     *      methods=["GET"]
     * )
     */
    #[Route("/card/deck", name: "deck")]
    public function deck(sessionInterface $session): Response
    {   
        $deck = new DeckOfCards;
        $session->set('deck', $deck);

        $deckObject = $session->get('deck');

        $data = [
            'cards' => $deckObject->getAllCards(),
            'amount' => $deckObject->countCards()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    /**
     * @Route(
     *      "/card/deck/shuffle",
     *      name="shuffle_deck",
     *      methods=["GET"]
     * )
     */
    #[Route("/card/deck/shuffle", name: "shuffle_deck")]
    public function shuffleDeck(sessionInterface $session): Response
    {
        $deckObject = $session->get('deck');
        $deckObject->shuffleDeck();
        $session->set('deck', $deckObject);
        

        $data = [
            'cards' => $deckObject->getAllCards(),
            'amount' => $deckObject->countCards()
        ];

        return $this->render('card/shuffle.html.twig', $data);
    }

    /**
     * @Route(
     *      "/card/deck/draw",
     *      name="draw_deck",
     *      methods=["GET"]
     * )
     */
    #[Route("/card/deck/draw", name: "draw_deck", methods: ['GET'])]
    public function drawDeck(sessionInterface $session): Response
    {
        $deckObject = $session->get('deck');
        $faceDown = new CardGraphic;
        $drawnCard = $session->get('drawn_card');
        $drawnCards = $session->get('drawn_cards');
        

        $data = [
            'cards' => $deckObject->getAllCards(),
            'amount' => $deckObject->countCards(),
            'down' => $faceDown,
            'drawnCard' => $drawnCard,
            'drawnCards' => $drawnCards
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    /**
     * @Route(
     *      "/card/deck/draw",
     *      name="draw_deck_post",
     *      methods=["POST"]
     * )
     */
    #[Route("/card/deck/draw", name: "draw_deck_post", methods: ['POST'])]
    public function drawDeckPost(Request $request, sessionInterface $session): Response
    {
        $deckObject = $session->get('deck');

        if ($request->request->has('number')) {
            $number = $request->request->get('number');
            $drawnCards = $deckObject->drawCard($number);
            $session->set('drawn_cards', $drawnCards);
        } else {
            $drawnCard = $deckObject->drawCard();
            $session->set('drawn_card', $drawnCard);
        }
        $session->set('deck', $deckObject);

        return $this->redirectToRoute('draw_deck');
    }

    /**
     * @Route(
     *      "/card/deck/draw//{number<\d+>}",
     *      name="draw_deck_more",
     *      methods=["GET"]
     * )
     */
    #[Route("/card/deck/draw/{number<\d+>}", name: "draw_deck_more", methods: ['GET'])]
    public function drawDeckMore(int $number, sessionInterface $session): Response
    {
        $deckObject = $session->get('deck');
        
        $drawnCards = $deckObject->drawCard($number);

        $session->set('deck', $deckObject);
        $session->set('drawn_cards', $drawnCards);
        

        return $this->redirectToRoute('draw_deck');
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
        $cardNull = new CardGraphic;
        $hand = new CardHand;
        $hand->add($cardNull);
        for ($i = 0; $i < 5; $i++) {
            $card = new CardGraphic;
            $card->draw();
            $hand->add($card);
        }

        $session->set('card', $card);
        $session->set('hand', $hand);
        $session->set('null_card', $cardNull);

        $data = [
            'drawn_card' => $session->get('drawn_card'),
            'drawn_cards' => $session->get('drawn_cards'),
            'card' => $session->get('card'),
            'hand' => $session->get('hand'),
            'deck' => $session->get('deck'),
            'null_card' => $session->get('null_card'),
            'data' => $session->all()

        ];

        return $this->render('card/session.html.twig', $data);
    }

    /**
     * @Route(
     *      "/session/delete",
     *      name="session_delete"
     * )
     */
    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(SessionInterface $session): Response
    {
        $session->clear();

        $this->addFlash(
            'notice',
            'Sessionen är nu raderad!'
        );

        return $this->redirectToRoute('deck');
    }
}