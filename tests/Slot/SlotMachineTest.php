<?php

namespace App\Slot;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class SlotMachine.
 */
class SlotMachineTest extends TestCase
{
    /**
     * Construct the class.
     */
    public function testSlotConstruct(): void
    {
        # Arrange
        $slotMachine = new SlotMachine();

        # Assert
        $this->assertInstanceOf("\App\Slot\SlotMachine", $slotMachine);
    }
    /**
     * Test getSymbols returns expected array
     */
    public function testGetSymbols(): void
    {
        $slotMachine = new SlotMachine();
        $expected = ['cherry', 'diamond', 'bell', 'seven', 'clover', 'lemon', 'coin'];

        $this->assertEquals($expected, $slotMachine->getSymbols());
    }

    /**
     * Test the spin method.
     * Should return an array with 3 elements.
     * Should return correct symbols.
     */
    public function testSpinReturnCorrect(): void
    {
        # Arrange
        $slotMachine = new SlotMachine();

        # Act
        $result = $slotMachine->spin();
        $expSymbols = $slotMachine->getSymbols();

        # Assert
        $this->assertCount(3, $result);

        foreach ($result as $symbol) {
            $this->assertContains($symbol, $expSymbols);
        }
    }

    /**
     * Test the calculate win method.
     * Trying out all different ways.
     */
    public function testCalculateWin(): void
    {
        # Arrange
        $slotMachine = new SlotMachine();

        # Try jackpot
        # Act
        $res = $slotMachine->calculateWin('cherry', 'cherry', 'cherry');
        $expMsg = 'JACKPOT! Tre ' . 'cherry' . '!';


        # Assert
        $this->assertTrue($res['won']);
        $this->assertSame($res['message'], $expMsg);
        $this->assertEquals(100, $res['amount']);

        # Try 2 slots equals.
        # Act
        $res2 = $slotMachine->calculateWin('coin', 'coin', 'seven');
        $expMsg2 = 'Vinst! Två ' . 'coin' . '!';

        # Assert
        $this->assertTrue($res2['won']);
        $this->assertSame($res2['message'], $expMsg2);
        $this->assertEquals(10, $res2['amount']);

    }

}
