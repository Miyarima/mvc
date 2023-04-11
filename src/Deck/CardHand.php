<?php

namespace App\Deck;

use App\Deck\Card;

class CardHand
{
    private $hand = [];
    private $cardCount = 0;

    public function add(Card $card): void
    {
        $this->hand[] = $card;
        $this->cardCount++;
    }

    public function getNumberCards(): int
    {
        return $this->cardCount;
    }

    public function getCards(): array
    {
        return $this->hand;
    }

    public function getSpecificCard($pos): Card
    {
        $this->hand = array_values($this->hand);
        return $this->hand[$pos];
    }

    public function removeCard($name): void
    {
        $pos = array_search($name, array_map(function ($card) {
            return $card->getName();
        }, $this->hand));

        if ($pos !== false) {
            unset($this->hand[$pos]);
        }
        $this->cardCount--;
    }
}