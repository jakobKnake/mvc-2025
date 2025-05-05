<?php

namespace App\Card;

/**
 * Interface for GameLogic class.
 */
interface GameInterface
{
    /**
     * Add a player to the game.
     * @param string $name The name of the player.
     * @return void
     */
    public function addPlayer(string $name);

    /**
     * Start the game.
     * Initialize the classes for the game.
     * Shuffle deck and start dealing.
     * @return void
     */
    public function startGame();

    /**
     * Deal card to a player or the dealer.
     * @param PlayerInterface $dealTo The one to deal card to.
     * @return Card The dealt card.
     */
    public function dealCardTo(PlayerInterface $dealTo);

    /**
     * Get all the players
     * @return array<Player> The players.
     */
    public function getPlayers();

    /**
     * Get the dealer.
     * @return Dealer The dealer.
     */
    public function getDealer();

    /**
     * Decide the winner or winners of the game.
     * Compare player hand with dealer to set outcome.
     * @return array<string,string> Array with name of player and outcome for that player.
     */
    public function decideWinner();

    /**
     * Player decides to stand (Not drawing).
     * @return bool True if stand.
     */
    public function playerStand();

    /**
     * Player decides to hit (draw a card).
     * @return bool False if player is busted true if can continue.
     */
    public function playerHit();

    /**
     * Play the dealer turn.
     * Will draw until rules or busted is fullfilled.
     * @return void
     */
    public function playDealer();

    /**
     * Check the status of the player.
     * Can the player make another turn.
     * @return bool True if player can make another action, false if not.
     */
    public function canPlayerContinue();



}
