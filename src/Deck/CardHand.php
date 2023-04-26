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

    /**
     * Adds a card to the hand.
     */
    public function add(Card $card): void
    {
        $this->hand[] = $card;
        $this->cardCount++;
    }

    /**
     * Returns the number of cards in the hand.
     */
    public function getNumberCards(): int
    {
        return $this->cardCount;
    }

    /**
     * Returns the hand.
     * @return array<Card>
     */
    public function getCards(): array
    {
        return $this->hand;
    }

    /**
     * Returns a specific card from the hand.
     */
    public function getSpecificCard(int $pos): Card
    {
        $this->hand = array_values($this->hand);
        return $this->hand[$pos];
    }

    /**
     * Removes a card from the hand.
     */
    public function removeCard(string $name): void
    {
        $pos = array_search($name, array_map(function ($card) {
            return $card->getName();
        }, $this->hand));

        if ($pos !== false) {
            unset($this->hand[$pos]);
            $this->cardCount--;
        }
    }
}
