<?php

namespace App\Game;

use App\Deck\Card;
use App\Deck\CardHand;
use App\Deck\DeckOfCards;

class BlackJack
{
    private CardHand $playerHand;
    private CardHand $houseHand;
    private DeckOfcards $deck;
    private array $values = [
        "2" => 2,
        "3" => 3,
        "4" => 4,
        "5" => 5,
        "6" => 6,
        "7" => 7,
        "8" => 8,
        "9" => 9,
        "10" => 10,
        "jack" => 10,
        "queen" => 10,
        "king" => 10,
        "ace" => 11
    ];

    public function __construct() {
        $this->playerHand = new CardHand();
        $this->houseHand = new CardHand();
        $this->deck = new DeckOfCards();
    }

    public function setDeck(array $removed): void
    {
        for ($i = 1; $i <= 52; $i++) {
            $this->deck->add(new Card());
        }

        if($removed !== null) {
            foreach($removed as $remove) {
                $this->deck->removeCard($remove);
            }
        }
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function shuffleDeck(): void
    {
        if ($this->deck->getNumberCards() > 51) {
            $this->deck->shuffleDeck();
        }
    }

    public function setPlayer(array $hand): void
    {
        foreach ($hand as $name) {
            $card = new Card();
            $card->setName($name);
            $this->playerHand->add($card);
        }
    }

    // public function setHouse(array $hand): void
    // {

    // }

    public function getPlayer(): array
    {
        $cards = [];
        foreach ($this->playerHand->getCards() as $card) {
            $cards[] = $card->getName();
        }
        return $cards;
    }

    public function getHouse(): CardHand
    {
        return $this->houseHand;
    }

    public function playerPoints(): int
    {   
        $total = 0;
        foreach ($this->playerHand->getCards() as $card) {
            $val = explode("_", $card->getName());
            $total += $this->values[$val[0]];
        }
        return $total;
    }
}
