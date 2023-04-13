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
}
