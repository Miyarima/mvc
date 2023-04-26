<?php

namespace App\Deck;

class Card
{
    private string $name;

    public function __construct()
    {
        $this->name = "";
    }

    /**
     * Returns the name of the card.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the card.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
