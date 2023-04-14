<?php

namespace App\Deck;

use App\Deck\Card;

class CardHand
{
    /**
     * @var array<Card> $hand
     */
    private array $hand = [];

    /**
    * @var int $cardCount
    */
    private int $cardCount = 0;

    public function add(Card $card): void
    {
        $this->hand[] = $card;
        $this->cardCount++;
    }

    public function getNumberCards(): int
    {
        return $this->cardCount;
    }

    /**
     * @return array<Card>
     */
    public function getCards(): array
    {
        return $this->hand;
    }

    public function getSpecificCard(int $pos): Card
    {
        $this->hand = array_values($this->hand);
        return $this->hand[$pos];
    }

    public function removeCard(string $name): void
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
