<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Player class.
 */
class PlayerTest extends TestCase
{
    
    /**
     * Test the constructor.
     */
    public function testPlayerConstruct()
    {
        # Arrange
        $rules = new BlackJackRules();
        $player = new Player("jake", $rules);

        # Assert
        $this->assertInstanceOf("\App\Card\Player", $player);
    }

    /**
     * Test method to add cards to player hand.
     * See if player is busted or not.
     */
    public function testAddCardToPlayer()
    {
        # Arrange
        $rules = new BlackJackRules();
        $player = new Player("jake", $rules);
        $player2 = new Player("jol", $rules);

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "4");
        $card3->setCard("Diamonds", "10");

        # Act
        $player->addCard($card1);
        $player->addCard($card2);

        $player2->addCard($card1);
        $player2->addCard($card2);
        $player2->addCard($card3);

        $res = $player->getHand();

        # Assert
        $this->assertCount(2, $res);
        $this->assertSame($card1, $res[0]);
        $this->assertSame($card2, $res[1]);
        $this->assertFalse($player->isBusted());
        $this->assertTrue($player2->isBusted());

    }

    /**
     * Test if player has black jack.
     */
    public function testHasPlayerBlackJack()
    {
        $rules = new BlackJackRules();
        $player = new Player("jake", $rules);
        $player2 = new Player("jol", $rules);

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "4");
        $card3->setCard("Diamonds", "Ace");

        # Act
        $player->addCard($card1);
        $player->addCard($card2);

        $player2->addCard($card1);
        $player2->addCard($card3);

        # Assert
        $this->assertFalse($player->hasBlackJack());
        $this->assertTrue($player2->hasBlackJack());
    }

    /**
     * Test getScore method.
     */
    public function testGetPlayerScore()
    {
        $rules = new BlackJackRules();
        $player = new Player("jake", $rules);
        $player2 = new Player("jol", $rules);

        $card1 = new Card();
        $card2 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "4");

        $player->addCard($card1);
        $player->addCard($card2);

        # Act
        $res = $player->getScore();
        $res2 = $player2->getScore();

        # Assert
        $this->assertSame(14, $res);
        $this->assertSame(0, $res2);
    }

    /**
     * Test stand and isStanding method.
     */
    public function testPlayerIsStanding()
    {
        # Arrange
        $rules = new BlackJackRules();
        $player = new Player("jake", $rules);

        $this->assertFalse($player->isStanding()); # Not standing yet should return false.

        # Act
        $player->stand();

        $res = $player->isStanding();

        # Assert
        $this->assertTrue($res);
    }
}