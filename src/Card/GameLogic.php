<?php

namespace App\Card;

/**
 * Class that handles the game logic of black jack.
 */
class GameLogic implements GameInterface
{
    /**
     * @var BlackJackRules $rules The rules of the game.
     */
    protected $rules;

    /**
     * @var array<Player> $players The players in the game.
     */
    protected $players;

    /**
     * @var Dealer $dealer The dealer in the game.
     */
    protected $dealer;

    /**
     * @var DeckOfCards $deck The deck of cards.
     */
    protected $deck;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rules = new BlackJackRules();
        $this->dealer = new Dealer($this->rules);
        $this->deck = new DeckOfCards();
        $this->players = [];
    }

    /**
     * Add a player to the game.
     * @param string $name The name of the player.
     */
    public function addPlayer(string $name): void
    {
        $this->players[] = new Player($name, $this->rules);
    }

    /**
     * Start a new game.
     * Shuffle the deck and deal the initial cards.
     */
    public function startGame(): void
    {
        $this->deck->shuffleDeck();

        for ($i = 0; $i < 2; $i++) {
            foreach ($this->players as $player) {
                $this->dealCardTo($player);
            }
            $this->dealCardTo($this->dealer);
        }
    }

    /**
     * Start a new game with other features.
     * This is used in kmom10.
     * Handles multiple hands.
     */
    public function startProjGame(): void
    {
        $this->deck->shuffleDeck();

        for ($i = 0; $i < 2; $i++) {
            foreach ($this->players as $player) {
                $hands = $player->getHands();

                foreach ($hands as $handIndex => $hand) {
                    $this->dealToHand($player, $handIndex);
                }
            }
            $this->dealCardTo($this->dealer);
        }

    }
    

    /**
     * Deal card to either player or dealer.
     * @param PlayerInterface $dealTo The one to deal to.
     * @return mixed The dealt card.
     */
    public function dealCardTo(PlayerInterface $dealTo): mixed
    {
        $card = $this->deck->drawCard();

        if ($card instanceof Card) {
            $dealTo->addCard($card);
        }

        return $card;
    }

    /**
     * Deal card to hand.
     * @param Player $player The player.
     * @param int $handIndex The hand to deal to.
     * @return mixed The dealt card.
     */
    public function dealToHand(Player $player, int $handIndex): mixed
    {
        $card = $this->deck->drawCard();

        if ($card instanceof Card) {
            $hands = $player->getHands();

            if (isset($handIndex)) {
                $hands[$handIndex]->add($card);
            }
        }

        return $card;
    }

    /**
     * Get all the players
     * @return array<Player> The players.
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * Get the dealer.
     * @return Dealer The dealer.
     */
    public function getDealer(): Dealer
    {
        return $this->dealer;
    }

    /**
     * Play the dealer turn.
     * Will draw until rules or busted is fullfilled.
     * @return void
     */
    public function playDealer(): void
    {
        $dealer = $this->getDealer();

        while (!$dealer->isBusted() && $dealer->shouldDraw()) {
            $this->dealCardTo($dealer);
        }

        $dealer->stand();
    }

    /**
     * Player decides to stand (Not drawing).
     * @return bool True if stand, false if no player exists.
     */
    public function playerStand(): bool
    {
        $players = $this->getPlayers();

        if (empty($players)) {
            return false;
        }
        $currentHand = $players[0]->getCurrentHand();
        $currentHand->standHand();

        $players[0]->nextHand();

        $numberOfHands = count($players[0]->getHands());

        if ($players[0]->getCurrentHandIndex() >= $numberOfHands) {
            $players[0]->stand();
            return $players[0]->isStanding();
        }

        return false;

    }

    /**
     * Player decides to hit (draw a card).
     * @return bool False if player is busted true if hit.
     */
    public function playerHit(): bool
    {
        $players = $this->getPlayers();

        if (empty($players)) {
            return false;
        }

        $player = $players[0];

        if ($player->isStanding() || $player->isBusted()) {
            return false;
        }

        $currentHand = $player->getCurrentHand();
        $handIndex = $player->getCurrentHandIndex();
        if ($currentHand->isHandStanding()) {
            return false;
        }


        $this->dealToHand($player, $handIndex);

        if ($this->rules->busted($currentHand)) {
            $player->nextHand();
        }

        return true;

    }

    /**
     * Player decides to split his cards.
     * @return bool False if player is busted true if split.
     */
    public function playerSplit(): bool
    {
        $players = $this->getPlayers();

        if (empty($players)) {
            return false;
        }

        $player = $players[0];

        if ($player->isBusted()) {
            return false;
        }

        $currentHand = $player->getCurrentHand();

        if (!$this->rules->canPlayerSplit($currentHand)) {
            return false;
        }

        $cards = $currentHand->getCards();
        $card1 = $cards[0];
        $card2 = $cards[1];

        $currentHand->clearHand();
        $currentHand->add($card1);

        $newHand = new CardHand();
        $newHand->add($card2);
        $player->addHand($newHand);

        $currentHand->add($this->deck->drawCard());
        $newHand->add($this->deck->drawCard());

        return true;
    }

    /**
     * Check the status of the player.
     * Can the player make another turn.
     * @return bool True if player can make another action, false if not.
     */
    public function canPlayerContinue(): bool
    {
        $players = $this->getPlayers();

        if (empty($players)) {
            return false;
        }

        $player = $players[0];
        $numberOfHands = $player->getNumbersHands();

        if ($player->getCurrentHandIndex() >= $numberOfHands) {
            return false;
        }

        if ($player->isStanding() || $player->isBusted()) {
            return false;
        }

        $currentHand = $player->getCurrentHand();

        return !($currentHand->isHandStanding() || $this->rules->busted($currentHand));
    }

    /**
     * Decide the winner or winners of the game.
     * Compare player hand with dealer to set outcome.
     * @return array<string,string> Array with name of player and outcome for that player.
     */
    public function decideWinner(): array
    {
        $result = [];

        $dealer = $this->getDealer();
        $dealerScore = $dealer->getScore();
        $dealerBusted = $dealer->isBusted();
        $dealerBlackJack = $dealer->hasBlackJack();

        $players = $this->getPlayers();

        foreach ($players as $player) {
            $playerName = $player->getName();
            $hands = $player->getHands();

            $wins = 0;
            $losses = 0;
            $busts = 0;
            $blackjacks = 0;
            $pushes = 0;

            foreach ($hands as $hand) {
                $handScore = $this->rules->calculateHand($hand);
                $handBust = $this->rules->busted($hand);
                $handBj = $this->rules->isBlackJack($hand);

                if ($handBust) {
                    $busts++;
                } elseif ($dealerBusted) {
                    $wins++;
                } elseif ($handBj && !$dealerBlackJack) {
                    $blackjacks++;
                } elseif ($handScore > $dealerScore) {
                    $wins++;
                } elseif ($handScore < $dealerScore) {
                    $losses++;
                } else {
                    $pushes++;
                }
            }
            
            $result[$playerName] = "Dina händer:\nWin: $wins\nLoss: $losses\nBust: $busts\nBlackJack: $blackjacks\nPush: $pushes";

        }

        return $result;

    }
}
