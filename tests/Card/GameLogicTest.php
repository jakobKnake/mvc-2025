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
     * Test start project game.
     * Handle more hands for one player.
     */
    public function testStartProjGame(): void
    {
        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake");

        $player = $game->getPlayers()[0];

        $cardHand2 = new CardHand();
        $cardHand3 = new CardHand();
        $cardHand4 = new CardHand();

        $player->addHand($cardHand2);
        $player->addHand($cardHand3);
        $player->addHand($cardHand4);

        # Act
        $game->startProjGame();

        # Assert
        # Assert
        $this->assertSame(4, $player->getNumbersHands()); // 4 as one hand is initialized in player construct.

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

        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake2");
        $player2 = $game->getPlayers()[0];
        $currentHand2 = $player2->getCurrentHand();
        
        $card1 = new Card();
        $card1->setCard('Spades', '7');
        $currentHand2->add($card1);
        $currentHand2->standHand();

        # Act
        $res2 = $game->playerHit();

        # Assert
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

        # Arrange
        $game = new GameLogic();

        $game->addPlayer("jake2");
        $player2 = $game->getPlayers()[0];
        $currentHand = $player2->getCurrentHand();
        $cardHand2 = new CardHand();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card1->setCard('Spades', '10');
        $card2->setCard('Spades', '10');
        $card3->setCard('Spades', 'Ace');
        $currentHand->add($card1);
        $currentHand->add($card2);
        $currentHand->add($card3);

        $cardHand2->add($card1);

        $player2->addHand($cardHand2);

        # Act
        $res3 = $game->playerHit();
        $handsamount = $player2->getHands();
        $index = $player2->getCurrentHandIndex();

        # Assert
        $this->assertCount(2, $handsamount);
        $this->assertTrue($res3);
        $this->assertEquals(1, $index);
        
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

        $player = $game->getPlayers()[0];
        $currentHand = $player->getCurrentHand();
        $card1 = new Card();
        $card2 = new Card();
        $card1->setCard('Spades', '7');
        $card2->setCard('Hearts', '7');
        $currentHand->add($card1);
        $currentHand->add($card2);

        $game->playerSplit();

        # Act
        $res = $game->playerStand();

        # Assert
        $this->assertFalse($res);

        # Act
        $res2 = $game->playerStand();

        # Assert
        $this->assertTrue($res2);
        $this->assertTrue($player->isStanding());

    }


    /**
     * Test decideWinner method with mock.
     */
    public function testDecideWinner(): void
    {
        # Arrange
        $game = $this->getMockBuilder(GameLogic::class)
            ->onlyMethods(['getDealer', 'getPlayers'])
            ->getMock();

        $dealer = $this->createMock(Dealer::class);
        $player1 = $this->createMock(Player::class);

        $dealer->method('getScore')->willReturn(19);
        $dealer->method('isBusted')->willReturn(false);
        $dealer->method('hasBlackJack')->willReturn(false);

        $player1->method('getName')->willReturn('Player1');
        $cardHand1 = new CardHand();
        $cardHand2 = new CardHand();
        $cardHand3 = new CardHand();
        $cardHand4 = new CardHand();
        $cardHand5 = new CardHand();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();
        $card5 = new Card();
        $card6 = new Card();
        $card1->setCard('Spades', '7');
        $card2->setCard('Hearts', '7');
        $card3->setCard('Spades', '10');
        $card4->setCard('Hearts', 'Ace');
        $card5->setCard('Spades', '10');
        $card6->setCard('Hearts', '9');

        $cardHand1->add($card1);
        $cardHand1->add($card2);

        $cardHand2->add($card3);
        $cardHand2->add($card4);

        $cardHand3->add($card5);
        $cardHand3->add($card6);
        
        $cardHand4->add($card3);
        $cardHand4->add($card5);
        $cardHand4->add($card1);

        $cardHand5->add($card3);
        $cardHand5->add($card5);
        $player1->method('getHands')->willReturn([$cardHand1, $cardHand2, $cardHand3, $cardHand4, $cardHand5]);


        $game->method('getDealer')->willReturn($dealer);
        $game->method('getPlayers')->willReturn([$player1]);

        # Act
        $res = $game->decideWinner();

        # Assert
        $this->assertEquals("Dina händer:<br>Win: 1<br>Loss: 1<br>Bust: 1<br>BlackJack: 1<br>Push: 1", $res['Player1']);
    }

    /**
     * Test decide winner with dealer busted.
     */
    public function testDecideWinnerDealerBust(): void
    {
        # Arrange
        $game = $this->getMockBuilder(GameLogic::class)
            ->onlyMethods(['getDealer', 'getPlayers'])
            ->getMock();

        $dealer = $this->createMock(Dealer::class);
        $player1 = $this->createMock(Player::class);
        $player2 = $this->createMock(Player::class);
        $player3 = $this->createMock(Player::class);

        $dealer->method('getScore')->willReturn(24);
        $dealer->method('isBusted')->willReturn(true);
        $dealer->method('hasBlackJack')->willReturn(false);

        $cardHand = new CardHand();
        $cardHand2 = new CardHand();
        $cardHand3 = new CardHand();
        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();
        $card5 = new Card();
        $card1->setCard('Spades', '10');
        $card2->setCard('Hearts', '10');
        $card3->setCard('Diamonds', '10');
        $card4->setCard('Hearts', 'Ace');
        $card5->setCard('Hearts', '2');

        $cardHand->add($card1);
        $cardHand->add($card2);
        $cardHand2->add($card3);
        $cardHand2->add($card4);
        $cardHand3->add($card1);
        $cardHand3->add($card2);
        $cardHand3->add($card5);

        $player1->addHand($cardHand);
        $player2->addHand($cardHand2);
        $player3->addHand($cardHand3);
    
        $player1->method('getHands')->willReturn([$cardHand]);
        $player1->method('getName')->willReturn('Player1');
        $player1->method('getNumbersHands')->willReturn(1);

        $player2->method('getHands')->willReturn([$cardHand2]);
        $player2->method('getName')->willReturn('Player2');
        $player2->method('getNumbersHands')->willReturn(1);

        $player3->method('getHands')->willReturn([$cardHand3]);
        $player3->method('getName')->willReturn('Player3');
        $player3->method('getNumbersHands')->willReturn(1);

        $game->method('getDealer')->willReturn($dealer);
        $game->method('getPlayers')->willReturn([$player1, $player2, $player3]);

        # Act
        $res = $game->decideWinner();

        # Assert
        $this->assertEquals("Win", $res['Player1']);
        $this->assertEquals("BlackJack", $res['Player2']);
        $this->assertEquals("Bust", $res['Player3']);
        
    }


    /**
     * Test playersplit method returning true and false.
     */
    public function testPlayerSplitTrue(): void
    {
        # Arrange
        $game = new GameLogic();

        # Utan spelare
        # Assert
        $this->assertFalse($game->playerSplit());


        $game->addPlayer("jake");

        $players = $game->getPlayers();
        $player = $players[0];

        $card1 = new Card();
        $card2 = new Card();
        $card1->setCard('Spades', '10');
        $card2->setCard('Hearts', '10');

        $currentHand = $player->getCurrentHand();
        $currentHand->add($card1);
        $currentHand->add($card2);

        # Act
        $res = $game->playerSplit();
        $yes = $game->canPlayerContinue();

        # Lyckad split
        # Assert
        $this->assertTrue($res);
        $this->assertTrue($yes);
        $this->assertEquals(2, $player->getNumbersHands());


        # Arrange
        $currentHand->clearHand();

        $card3 = new Card();
        $card2->setCard('Diamonds', '5');

        $currentHand->add($card1);
        $currentHand->add($card2);
        $currentHand->add($card3);

        # Act
        $res2 = $game->playerSplit();

        # Failed split, för många kort
        # Assert
        $this->assertFalse($res2);
    }

    /**
     * Test split method false.
     */
    public function testPlayerSplitFalse(): void
    {
        # Arrange
        $game = $this->getMockBuilder(GameLogic::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPlayers'])
            ->getMock();

        $player = $this->createMock(Player::class);
        $player->method('isBusted')->willReturn(true);
        $player->method('getCurrentHandIndex')->willReturn(10);

        $game->method('getPlayers')->willReturn([$player]);

        # Act
        $res = $game->playerSplit();
        $res2 = $game->canPlayerContinue();

        # Assert
        $this->assertFalse($res);
        $this->assertFalse($res2);
    }

}
