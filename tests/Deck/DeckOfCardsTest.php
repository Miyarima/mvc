<?php

namespace App\Deck;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct a DeckOfCards object with all cards.
     */
    public function testCreateDeckOfCards(): void
    {
        $deckOfCards = new DeckOfCards();
        $this->assertInstanceOf("\App\Deck\DeckOfCards", $deckOfCards);

        for ($i = 1; $i <= 52; $i++) {
            $deckOfCards->add(new Card());
        }

        $numbCards = $deckOfCards->getNumberCards();
        $this->assertEquals(52, $numbCards);
        $this->assertEquals("queen_of_spades", $deckOfCards->getSpecificCard(49)->getName());
    }

    /** 
     * Shuffle DeckOfCards.
     */
    public function testCreateShuffleDeckOfCards(): void
    {
        $deckOfCards = new DeckOfCards();

        for ($i = 1; $i <= 52; $i++) {
            $deckOfCards->add(new Card());
        }

        $deckBeforeShuffle = $deckOfCards->getCards();
        $deckOfCards->shuffleDeck();
        $deckAfterShuffle = $deckOfCards->getCards();
        $this->assertNotEquals($deckBeforeShuffle, $deckAfterShuffle);
    }

    /** 
     * Remove a card from a DeckOfCards object.
     */
    public function testCreateDeckOfCardsRemoveCard(): void
    {
        $deckOfCards = new DeckOfCards();

        for ($i = 1; $i <= 52; $i++) {
            $deckOfCards->add(new Card());
        }

        $numbCards = $deckOfCards->getNumberCards();
        $this->assertEquals(52, $numbCards);

        $beforeRemove = $deckOfCards->getCards();
        $beforeCards = [];
        foreach ($beforeRemove as $card) {
            $beforeCards[] = $card->getName();
        }

        $deckOfCards->removeCard("ace_of_hearts");
        $numbCards = $deckOfCards->getNumberCards();
        $this->assertEquals(51, $numbCards);

        $res = $deckOfCards->getCards();
        $resCards = [];
        foreach ($res as $card) {
            $resCards[] = $card->getName();
        }

        $this->assertNotEquals($beforeCards, $resCards);
    }

    /** 
     * Remove a card from a CardHand object that doesnt exist.
     */
    public function testCreateDeckOfCardsRemoveCardThatDoesntExist(): void
    {
        $deckOfCards = new DeckOfCards();

        for ($i = 1; $i <= 52; $i++) {
            $deckOfCards->add(new Card());
        }

        $numbCards = $deckOfCards->getNumberCards();
        $this->assertEquals(52, $numbCards);

        $beforeRemove = $deckOfCards->getCards();
        $beforeCards = [];
        foreach ($beforeRemove as $card) {
            $beforeCards[] = $card->getName();
        }

        $deckOfCards->removeCard("ace_of_squares");
        $numbCards = $deckOfCards->getNumberCards();
        $this->assertEquals(52, $numbCards);

        $res = $deckOfCards->getCards();
        $resCards = [];
        foreach ($res as $card) {
            $resCards[] = $card->getName();
        }

        $this->assertEquals($beforeCards, $resCards);
    }
}