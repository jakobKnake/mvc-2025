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
        $players[0]->stand();

        return $players[0]->isStanding();

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

        $this->dealCardTo($player);

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

        return !($player->isStanding() || $player->isBusted());
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
            $playerName = $player->name;
            $playerScore = $player->getScore();
            $playerBusted = $player->isBusted();
            $playerBlackJack = $player->hasBlackJack();

            $playerOutcome = "Push";

            if ($playerBusted) {
                $playerOutcome = "Bust";
            } elseif ($dealerBusted) {
                $playerOutcome = "Win";
            } elseif ($playerBlackJack && !$dealerBlackJack) {
                $playerOutcome = "Black Jack";
            } elseif ($playerScore > $dealerScore) {
                $playerOutcome = "Win";
            } elseif ($playerScore < $dealerScore) {
                $playerOutcome = "Loss";
            }

            $result[$playerName] = $playerOutcome;

        }

        return $result;

    }
}
