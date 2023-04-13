<?php

namespace App\Deck;

class Card
{
    private string $name;

    public function __construct()
    {
        $this->name = "";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
