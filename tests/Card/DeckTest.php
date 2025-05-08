<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for DeckOfCards class.
 */
class DeckTest extends TestCase
{
    /**
     * Construct the object.
     * Should be 52 cards in deck.
     */
    public function testConstructDeck()
    {
        # Arrange
        $deck = new DeckOfCards();

        $exp = $deck->countCards();

        # Assert
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
        $this->assertEquals(52, $exp);

    }

    /**
     * Test shuffle desk.
     */
    public function testShuffle()
    {
        # Arrange
        $deck = new DeckOfCards();

        # Check that the deck is not shuffled.
        # First card should be spades 10.
        # Last diamonds king.
        $res = $deck->getAllCards();
        $first = $res[0]->getCardAsString();
        $last = end($res)->getCardAsString();

        # Assert
        $this->assertEquals("🂡", $first);
        $this->assertEquals("🃎", $last);

        # Act
        $deck->shuffleDeck();

        # Arrange
        $shuffled = $deck->getAllCards();
        $shuffCard = $shuffled[0]->getCardAsString();
        $shuffleCardSec = end($shuffled)->getCardAsString();

        # Assert
        $this->assertNotSame($first, $shuffCard);
        $this->assertNotSame($last, $shuffleCardSec);

    }
    /**
     * Test draw card method with no parameter.
     * Should return 1 card as default.
     */
    public function testDrawNoParam()
    {
        # Arrange
        $deck = new DeckOfCards();
        $cards = $deck->getAllCards();

        $expCard = $cards[0];

        # Act
        $res = $deck->drawCard();

        # Assert
        $this->assertInstanceOf("\App\Card\CardGraphic", $res);
        $this->assertSame($expCard, $res);

    }
    /**
     * Test draw card method with parameters.
     */
    public function testDrawMultiple()
    {
        # Arrange
        $deck = new DeckOfCards();
        $cards = $deck->getAllCards();

        $expCard = $cards[0];
        $expCardTwo = $cards[1];

        # Act
        $res = $deck->drawCard(2);
        $amount = $deck->countCards();

        # Assert
        $this->assertEquals(50, $amount);
        $this->assertCount(2, $res);
        $this->assertSame($expCard, $res[0]);
        $this->assertSame($expCardTwo, $res[1]);

        # Draw out of bound. Should automatically draw 50. Not 51.
        # Act
        $resTwo = $deck->drawCard(51);
        $amountTwo = $deck->countCards();

        # Assert
        $this->assertCount(50, $resTwo);
        $this->assertEquals(0, $amountTwo);

        # When deck is empty try to draw.
        # Should return null.

        # Act
        $resThree = $deck->drawCard(4);

        # Assert
        $this->assertNull($resThree);



    }

}