<?php

namespace App\Controller;

use App\Card\GameLogic;
use App\Entity\Project\User;
use App\Entity\Project\History;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use DateTime;


class ProjApiController extends AbstractController
{
    #[Route("/proj/api", name: "proj_api")]
    public function projIndex(): Response
    {
        $apiRoutes = [
            [
                'path' => '/proj/api/user',
                'name' => 'proj_api_user',
                'method' => 'GET',
                'description' => 'Visar inloggad användare.'
            ],
            [
                'path' => '/proj/api/bets',
                'name' => 'proj_api_bets',
                'method' => 'GET',
                'description' => 'Visar aktuella bets användaren gjort på nuvarande blackjack spel.'
            ],
            [
                'path' => '/proj/api/game_status',
                'name' => 'proj_api_game_status',
                'method' => 'GET',
                'description' => 'Retunerar nuvarande status på spelet för spelaren med antal händer.'
            ],
            [
                'path' => '/proj/api/history/{username}',
                'name' => 'proj_api_history',
                'method' => 'GET',
                'description' => 'Visar historiken för vald konto med username.'
            ],
            [
                'path' => '/proj/api/data',
                'name' => 'proj_api_data',
                'method' => 'GET',
                'description' => 'Visar användare i databasen.'
            ],
            [
                'path' => '/proj/api/add_balance',
                'name' => 'proj_api_balance',
                'method' => 'POST',
                'description' => 'Lägg till pengar till inloggad användares saldo med POST.'
            ],

        ];


        return $this->render('proj/api.html.twig', ['routes' => $apiRoutes]);
    }

    #[Route("/proj/api/user", name: "proj_api_user")]
    public function projApiUser(SessionInterface $session): Response
    {
        /** @var User|null $user */
        $user = $session->get('user');

        if (!$user) {
            return new JsonResponse(['error' => 'Logga in för att få åtkomst till denna route.'], 404);
        }

        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'pic' => $user->getProfilePic()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/proj/api/bets", name: "proj_api_bets")]
    public function projApiBets(SessionInterface $session): Response
    {
        /** @var array<int, array<string|int>> $bets */
        $bets = $session->get('bets');

        if (empty($bets)) {
            return new JsonResponse(['error' => 'Inga bets lagda än. Starta ett spel och lägg insatser'], 404);
        }

        $data = [];
        foreach ($bets as $handIndex => $bet) {
            $totalBet = array_sum(array_map('intval', $bet));
            $data[] = [
                'hand_index' => $handIndex,
                'token_bet' => $bet,
                'total_bet' => $totalBet
            ];
        }

        $response = new JsonResponse($data);
    
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        
        return $response;
    }

    #[Route("/proj/api/game_status", name: "proj_api_game_status")]
    public function projApiGameStatus(SessionInterface $session): Response
    {
        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return new JsonResponse(['error' => 'Spelet har inte initierats'], 400);
        }

        $player = $game->getPlayers()[0];

        $amountHands = $player->getNumbersHands();
        $hands = $player->getHands();

        $handCards = [];
        foreach ($hands as $handIndex => $hand) {
            $cards = $hand->getCards();

            $handCards[$handIndex] = [];
            foreach ($cards as $card) {
                $handCards[$handIndex][] = $card->getCardAsString();
            }
        }

        $data = [
            'name' => $player->getName(),
            'tot_hands' => $amountHands,
            'cards_in_hands' => $handCards,
        ];

        $response = new JsonResponse($data);
    
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        
        return $response;
    }

    #[Route("/proj/api/data", name: "proj_api_data")]
    public function projApiData(ManagerRegistry $doctrine): Response
    {
        $projEntityManager = $doctrine->getManager('project');
        
        $users = $projEntityManager->getRepository(User::class)->findAll();


        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'balance' => $user->getBalance(),
                'pic' => $user->getProfilePic()
            ];
        }

        $response = new JsonResponse($data);
    
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        
        return $response;

    }

    #[Route("/proj/api/history/{username}", name: "proj_api_history")]
    public function projApiHistory(string $username, ManagerRegistry $doctrine): Response
    {
        $projEntityManager = $doctrine->getManager('project');

        $userRepo = $projEntityManager->getRepository(User::class);

        $user = $userRepo->findOneBy(['username' => $username]);

        if (!$user) {
            return new JsonResponse(['error' => 'Ingen användare hittades med det användarnamnet'], 404);
        }

        $histories = $user->getHistories();

        $data = [];
        foreach ($histories as $history) {
            $data[] = [
                'action' => $history->getActionType(),
                'amount' => $history->getAmount(),
                'description' => $history->getDescription(),
                'created' => $history->getCreated()?->format('Y-m-d H:i:s') ?? 'Tomt på datum'
            ];
        }

        $response = new JsonResponse($data);
    
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        
        return $response;

    }

    #[Route("/proj/api/add_balance", name: "proj_api_balance", methods: ['POST'])]
    public function projApiAddBalance(ManagerRegistry $doctrine, 
    Request $request, SessionInterface $session): Response
    {
        $projEntityManager = $doctrine->getManager('project');

        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$sessionUser) {
            return new JsonResponse(['error' => 'Du måste vara inloggad'], 401);
        }

        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if (!$user) {
            return new JsonResponse(['error' => 'Användare hittades inte'], 404);
        }

        $amount = $request->request->get('amount');

        if (!$amount || !is_numeric($amount) || $amount <= 0) {
            return new JsonResponse(['error' => 'Ogiltligt belopp'], 400);
        }

        $currentBalance = intval($user->getBalance());
        $newBalance = $currentBalance + intval($amount);
        $user->setBalance(strval($newBalance));

        $history = new History();
        $history->setUserId($user);
        $history->setActionType('Insättning');
        $history->setDescription('Saldo tillagt via API');
        $history->setAmount(strval($amount));
        $history->setCreated(new DateTime());

        $projEntityManager->persist($history);
        $projEntityManager->flush();
        $session->set('user', $user);

        $data = [
            'amount' => intval($amount),
            'new_balance' => $newBalance,
            'old_balance' => $currentBalance
        ];

        $response = new JsonResponse($data);
    
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        
        return $response;
    }
}
