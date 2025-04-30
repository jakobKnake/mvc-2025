<?php

namespace App\Dice;

class Dice
{
    /**
     * @var int|null $value The number on the dice.
     */
    protected $value;

    public function __construct()
    {
        $this->value = null;
    }

    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * @returns int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
