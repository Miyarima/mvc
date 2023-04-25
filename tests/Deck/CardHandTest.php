<?php

namespace App\Deck;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct a CardHand object, and add a card to it.
     */
    public function testCreateCardHand()
    {
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Deck\CardHand", $cardHand);

        $card = new Card();
        $card->setName("ace_of_spades");
        $cardHand->add($card);

        $numbCards = $cardHand->getNumberCards();
        $this->assertEquals(1, $numbCards);
        $this->assertEquals("ace_of_spades", $cardHand->getSpecificCard(0)->getName());
    }

    /**
     * Construct a CardHand object, and add multiple cards to it.
     */
    public function testCreateCardHandWithMultipleCards()
    {
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Deck\CardHand", $cardHand);

        $cardNames = ["ace_of_spades", "ace_of_hearts", "ace_of_clubs", "ace_of_diamonds"];

        foreach ($cardNames as $cardName) {
            $card = new Card();
            $card->setName($cardName);
            $cardHand->add($card);
        }

        $numbCards = $cardHand->getNumberCards();
        $this->assertEquals(4, $numbCards);
        $this->assertEquals("ace_of_clubs", $cardHand->getSpecificCard(2)->getName());

        $res = $cardHand->getCards();
        $resCards = [];
        foreach ($res as $card) {
            $resCards[] = $card->getName();
        }

        $this->assertEquals($cardNames, $resCards);
    }

    /** 
     * Remove a card from a CardHand object.
     */
    public function testCreatecardHandRemoveCard()
    {
        $cardHand = new CardHand();

        $cardNames = ["ace_of_spades", "ace_of_hearts", "ace_of_clubs", "ace_of_diamonds"];
        $cardNamesRemoved = ["ace_of_spades", "ace_of_clubs", "ace_of_diamonds"];

        foreach ($cardNames as $cardName) {
            $card = new Card();
            $card->setName($cardName);
            $cardHand->add($card);
        }

        $numbCards = $cardHand->getNumberCards();
        $this->assertEquals(4, $numbCards);

        $cardHand->removeCard("ace_of_hearts");
        $numbCards = $cardHand->getNumberCards();
        $this->assertEquals(3, $numbCards);

        $res = $cardHand->getCards();
        $resCards = [];
        foreach ($res as $card) {
            $resCards[] = $card->getName();
        }

        $this->assertEquals($cardNamesRemoved, $resCards);
    }

    /** 
     * Remove a card from a CardHand object that doesnt exist.
     */
    public function testCreatecardHandRemoveCardThatDoesntExist()
    {
        $cardHand = new CardHand();

        $cardNames = ["ace_of_spades", "ace_of_hearts", "ace_of_clubs", "ace_of_diamonds"];

        foreach ($cardNames as $cardName) {
            $card = new Card();
            $card->setName($cardName);
            $cardHand->add($card);
        }

        $numbCards = $cardHand->getNumberCards();
        $this->assertEquals(4, $numbCards);

        $cardHand->removeCard("king_of_spades");
        $numbCards = $cardHand->getNumberCards();
        $this->assertEquals(4, $numbCards);

        $res = $cardHand->getCards();
        $resCards = [];
        foreach ($res as $card) {
            $resCards[] = $card->getName();
        }

        $this->assertEquals($cardNames, $resCards);
    }
}