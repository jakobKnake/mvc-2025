<?php

namespace App\Slot;

/**
 * Class representing the Slot machine.
 */
class SlotMachine
{
    /**
     * @var array<int, string> $symbols The symbols on the machine.
     */
    private $symbols;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->symbols = ['cherry', 'diamond', 'bell', 'seven', 'clover', 'lemon', 'coin'];
    }

    /**
     * Spin the machine. Randomize the output.
     * @return array<int, string> $result
     */
    public function spin(): array
    {
        return [
            $this->symbols[array_rand($this->symbols)],
            $this->symbols[array_rand($this->symbols)],
            $this->symbols[array_rand($this->symbols)]
        ];
    }

    /**
     * Calculate the win of the slots spinned.
     * @return array<string, bool|int|string> $result
     */
    public function calculateWin(string $slot1, string $slot2, string $slot3): array
    {
        $result = [
            'won' => false,
            'message' => 'Ingen vinst, kör igen!',
            'amount' => 0
        ];

        if ($slot1 === $slot2 && $slot2 === $slot3) {

            $result = [
                'won' => true,
                'message' => 'JACKPOT! Tre ' . $slot1 . '!',
                'amount' => 100
            ];
        } elseif ($slot1 === $slot2 || $slot1 === $slot3 || $slot2 === $slot3) {
            $symbol = ($slot1 === $slot2) ? $slot1 : (($slot2 === $slot3) ? $slot2 : $slot1);

            $result = [
                'won' => true,
                'message' => 'Vinst! Två ' . $symbol . '!',
                'amount' => 10
            ];
        }
        return $result;
    }

    /**
     * Get the slot symbols.
    * @return array<int, string>
    */
    public function getSymbols(): array
    {
        return $this->symbols;
    }
}
