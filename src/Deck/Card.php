<?php

namespace App\Deck;

class Card
{
    private $name;

    public function __construct()
    {
        $this->name = "";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
}
