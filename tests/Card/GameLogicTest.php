<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for GameLogic class.
 *
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class GameLogicTest extends TestCase
{
    /**
     * Test the constructor
     */
    public function testGameLogicConstructor(): void
    {
        # Arrange
        $game = new GameLogic();

        $dealer = $game->getDealer();

        $players = $game->getPlayers();

        # Assert
        $this->assertInstanceOf("\App\Card\GameLogic", $game);
        $this->assertInstanceOf("\App\Card\Dealer", $dealer);
        $this->assertSame("Dealer", $dealer->name);
        $this->assertCount(0, $players);
    }

    /**
     * Test add players
     */
    public function testAddPlayers(): void
    {
        # Arrange
        $game = new GameLogic();

        # Act
        $game->addPlayer("jake");
        $game->addPlayer("jol");
        $game->addPlayer("bol");

        $players = $game->getPlayers();

        # Assert
        $this->assertCount(3, $players);

    }

    /**
     * Test deal card to player.
     */
    public function testDealCardToPlayer(): void
    {
        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake");
        $game->addPlayer("jol");

        $players = $game->getPlayers();

        $player1 = $players[0];
        $player2 = $players[1];

        # Act
        /** @var CardGraphic $res */
        $res = $game->dealCardTo($player1);
        /** @var CardGraphic $res2 */
        $res2 = $game->dealCardTo($player2);

        $card = $res->getCardAsString();
        $card2 = $res2->getCardAsString();

        # Assert
        $this->assertInstanceOf("\App\Card\Card", $res);
        $this->assertSame("🂡", $card); # Not shuffled
        $this->assertSame("🂢", $card2);
        $this->assertInstanceOf("\App\Card\Card", $res2);
        $this->assertCount(1, $player1->getHand());

    }

    /**
     * Test to start a new game.
     * Should deal to every player and dealer.
     */
    public function testStartGame(): void
    {
        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake");
        $game->addPlayer("jol");
        $game->addPlayer("bol");

        $players = $game->getPlayers();
        $dealer = $game->getDealer();

        $player1 = $players[0];
        $player2 = $players[1];
        $player3 = $players[2];

        # Act
        $game->startGame();

        # Assert
        $this->assertCount(2, $player1->getHand());
        $this->assertCount(2, $player2->getHand());
        $this->assertCount(2, $player3->getHand());
        $this->assertCount(2, $dealer->getHand());
    }

    /**
     * Test to play dealer turn
     */
    public function testPlayDealer(): void
    {
        # Arrange
        $game = new GameLogic();

        $dealer = $game->getDealer();

        $initial = $dealer->getScore();

        #Assert
        $this->assertSame(0, $initial);

        # Act
        $game->playDealer();
        $res = $dealer->getScore();

        # Assert
        $this->assertTrue($dealer->isStanding());
        $this->assertNotSame($initial, $res);

    }

    /**
     * Test playerHit with no players.
     * Test playerStand with no players.
     * Test canPlayerContinue with no players.
     * Should return false.
     */
    public function testEmptyPlayers(): void
    {
        # Arrange
        $game = new GameLogic();

        # Act
        $res = $game->playerHit();
        $res2 = $game->playerStand();
        $res3 = $game->canPlayerContinue();

        # Assert
        $this->assertFalse($res);
        $this->assertFalse($res2);
        $this->assertFalse($res3);

    }

    /**
     * Test playerHit with busted or standing player.
     * The method is hard coded so always hit with players[0].
     */
    public function testPlayerHitFalse(): void
    {
        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake");


        $players = $game->getPlayers();

        $players[0]->stand();

        # Act
        $res = $game->playerHit();
        $res2 = $game->canPlayerContinue(); # Should be false as player is standing.

        # Assert
        $this->assertFalse($res);
        $this->assertFalse($res2);

    }

    /**
     * Test playerHit successfull.
     */
    public function testPlayerHitTrue(): void
    {
        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake");

        # Act
        $res = $game->playerHit();

        # Assert
        $this->assertTrue($res);
    }

    /**
     * Test playerStand.
     * Also hard coded as playerHit with players[0].
     */
    public function testPlayerStand(): void
    {
        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake");

        # Act
        $res = $game->playerStand();

        # Assert
        $this->assertTrue($res);

    }


    /**
     * Test decideWinner method with mock.
     */
    public function testDecideWinner(): void
    {
        # Arrange
        $game = $this->getMockBuilder(GameLogic::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDealer', 'getPlayers'])
            ->getMock();

        $dealer = $this->createMock(Dealer::class);
        $player1 = $this->createMock(Player::class);
        $player2 = $this->createMock(Player::class);
        $player3 = $this->createMock(Player::class);
        $player4 = $this->createMock(Player::class);
        $player5 = $this->createMock(Player::class);

        $dealer->method('getScore')->willReturn(19);
        $dealer->method('isBusted')->willReturn(false);
        $dealer->method('hasBlackJack')->willReturn(false);

        $player1->name = 'Player1';
        $player1->method('getScore')->willReturn(20);
        $player1->method('isBusted')->willReturn(false);
        $player1->method('hasBlackJack')->willReturn(false);

        $player2->name = 'Player2';
        $player2->method('getScore')->willReturn(17);
        $player2->method('isBusted')->willReturn(false);
        $player2->method('hasBlackJack')->willReturn(false);

        $player3->name = 'Player3';
        $player3->method('getScore')->willReturn(21);
        $player3->method('isBusted')->willReturn(false);
        $player3->method('hasBlackJack')->willReturn(true);

        $player4->name = 'Player4';
        $player4->method('getScore')->willReturn(24);
        $player4->method('isBusted')->willReturn(true);
        $player4->method('hasBlackJack')->willReturn(false);

        $player5->name = 'Player5';
        $player5->method('getScore')->willReturn(19);
        $player5->method('isBusted')->willReturn(false);
        $player5->method('hasBlackJack')->willReturn(false);


        $game->method('getDealer')->willReturn($dealer);
        $game->method('getPlayers')->willReturn([$player1, $player2, $player3, $player4, $player5]);

        # Act
        $res = $game->decideWinner();

        # Assert
        $this->assertEquals("Win", $res['Player1']);
        $this->assertEquals("Loss", $res['Player2']);
        $this->assertEquals("Black Jack", $res['Player3']);
        $this->assertEquals("Bust", $res['Player4']);
        $this->assertEquals("Push", $res['Player5']);

    }

    /**
     * Test decide winner with dealer busted.
     */
    public function testDecideWinnerDealerBust(): void
    {
        # Arrange
        $game = $this->getMockBuilder(GameLogic::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDealer', 'getPlayers'])
            ->getMock();

        $dealer = $this->createMock(Dealer::class);
        $player1 = $this->createMock(Player::class);

        $dealer->method('getScore')->willReturn(24);
        $dealer->method('isBusted')->willReturn(true);
        $dealer->method('hasBlackJack')->willReturn(false);

        $player1->name = 'Player1';
        $player1->method('getScore')->willReturn(20);
        $player1->method('isBusted')->willReturn(false);
        $player1->method('hasBlackJack')->willReturn(false);

        $game->method('getDealer')->willReturn($dealer);
        $game->method('getPlayers')->willReturn([$player1]);

        # Act
        $res = $game->decideWinner();

        # Assert
        $this->assertEquals("Win", $res['Player1']);
    }


}
