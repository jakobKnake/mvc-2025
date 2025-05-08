<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the BlackJackRules class.
 */
class BlackJackRulesTest extends TestCase
{
    /**
     * Test the calculateHand method.
     */
    public function testCalculateHand()
    {
        # Arrange
        $rules = new BlackJackRules();

        $firstHand = new CardHand();
        $secondHand = new CardHand();
        $thirdHand = new CardHand();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();
        $card5 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "5");
        $card3->setCard("Hearts", "King");
        $card4->setCard("Spades", "Queen");
        $card5->setCard("Spades", "Ace");


        $firstHand->add($card1);
        $firstHand->add($card2);

        $secondHand->add($card3);
        $secondHand->add($card4);

        # Act
        $firstRes = $rules->calculateHand($firstHand);
        $secondRes = $rules->calculateHand($secondHand);

        # Assert
        $this->assertEquals(15, $firstRes);
        $this->assertEquals(20, $secondRes);

        # Test that the ace now is worth 1.
        $secondHand->add($card5); # 20 + Ace should be 20 + 1.
        # Act
        $thirdRes = $rules->calculateHand($secondHand);

        # Assert
        $this->assertEquals(21, $thirdRes);

        # Check that Ace can be worth 11 when possible.
        $thirdHand->add($card5);

        # Act
        $ace11 = $rules->calculateHand($thirdHand);

        # Assert
        $this->assertEquals(11, $ace11);
    }

    /**
     * Test calculate empty hand
     */
    public function testCalculateEmptyHand()
    {
        # Arrange
        $rules = new BlackJackRules();

        $hand = new CardHand();

        # Act
        $res = $rules->calculateHand($hand);

        # Assert
        $this->assertEmpty($hand->getCards());
        $this->assertEquals(0, $res);

    }

    /**
     * Test if hand is blackjack.
     */
    public function testIsBlackJackTrue()
    {
        # Arrange
        $rules = new BlackJackRules();

        $hand = new CardHand();

        $card1 = new Card();
        $card2 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "Ace");

        $hand->add($card1);
        $hand->add($card2);

        # Act
        $res = $rules->isBlackJack($hand);

        # Assert
        $this->assertTrue($res);
    }

    /**
     * Test if hand is not blackjack.
     */
    public function testIsBlackJackFalse()
    {
        # Arrange
        $rules = new BlackJackRules();

        $hand = new CardHand();
        $hand2 = new CardHand();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "4");
        $card3->setCard("Diamonds", "7");

        $hand->add($card1);
        $hand->add($card2);
        $hand->add($card3);

        $hand2->add($card1);
        $hand2->add($card3);


        # Act
        $res = $rules->isBlackJack($hand); # Is 21 but 3 cards so not blackjack.
        $res2 = $rules->isBlackJack($hand2); # 2 cards but not enough.

        # Assert
        $this->assertFalse($res);
        $this->assertFalse($res2);
    }

    /**
     * Test the busted method.
     */
    public function testBusted()
    {
        # Arrange
        $rules = new BlackJackRules();

        $hand = new CardHand();
        $hand2 = new CardHand();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "4");
        $card3->setCard("Diamonds", "10");

        $hand->add($card1);
        $hand->add($card2);

        $hand2->add($card1);
        $hand2->add($card2);
        $hand2->add($card3);

        # Act
        $res = $rules->busted($hand);
        $res2 = $rules->busted($hand2);

        # Assert
        $this->assertFalse($res); # False as (10 + 4) < 21.
        $this->assertTrue($res2); # True 10 + 4 + 10 > 21.

    }

    /**
     * Test canDealerDraw
     * Bool.
     */
    public function testCanDealerDraw()
    {
        # Arrange
        $rules = new BlackJackRules();

        $hand = new CardHand();
        $hand2 = new CardHand();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "4");
        $card3->setCard("Diamonds", "10");

        $hand->add($card1);
        $hand->add($card2);

        $hand2->add($card1);
        $hand2->add($card3);

        # Act
        $res = $rules->canDealerDraw($hand);
        $res2 = $rules->canDealerDraw($hand2);

        # Assert
        $this->assertTrue($res); # 14 < 17 can draw.
        $this->assertFalse($res2); # 20 > 17 can not draw.
    }
}