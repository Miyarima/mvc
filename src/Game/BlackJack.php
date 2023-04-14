<?php

namespace App\Game;

use App\Deck\Card;
use App\Deck\CardHand;
use App\Deck\DeckOfCards;

class BlackJack
{
    private CardHand $playerHand;
    private CardHand $houseHand;
    private DeckOfCards $deck;

    /**
    * @var array<string, int> $values
    */
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

    public function __construct()
    {
        $this->playerHand = new CardHand();
        $this->houseHand = new CardHand();
        $this->deck = new DeckOfCards();
    }

    /**
     * @param array<string> $removed
     */
    public function setDeck(array $removed): void
    {
        for ($i = 1; $i <= 52; $i++) {
            $this->deck->add(new Card());
        }

        if($removed !== []) {
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

    /**
     * @param array<string> $hand
     */
    public function setPlayer(array $hand): void
    {
        $this->playerHand =  new CardHand();
        foreach ($hand as $name) {
            $card = new Card();
            $card->setName($name);
            $this->playerHand->add($card);
        }
    }

    /**
     * @param array<string> $hand
     */
    public function setHouse(array $hand): void
    {
        $this->houseHand =  new CardHand();
        foreach ($hand as $name) {
            $card = new Card();
            $card->setName($name);
            $this->houseHand->add($card);
        }
    }

    /**
     * @return array<string>
     */
    public function getPlayer(): array
    {
        $cards = [];
        foreach ($this->playerHand->getCards() as $card) {
            $cards[] = $card->getName();
        }
        return $cards;
    }

    /**
     * @return array<string>
     */
    public function getHouse(): array
    {
        $cards = [];
        foreach ($this->houseHand->getCards() as $card) {
            $cards[] = $card->getName();
        }
        return $cards;
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

    public function housePoints(): int
    {
        $total = 0;
        foreach ($this->houseHand->getCards() as $card) {
            $val = explode("_", $card->getName());
            $total += $this->values[$val[0]];
        }

        return $total;
    }

    // public function aceInHand(): bool
    // {

    // }
}
