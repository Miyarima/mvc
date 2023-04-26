<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJack.
 */
class BlackJackTest extends TestCase
{
    /** 
     * Construct a BlackJack object without arguments and check default values.
     */
    public function testCreateBlackJackCheckDefaultValues()
    {
        $blackJack = new BlackJack();
        $this->assertInstanceOf("\App\Game\BlackJack", $blackJack);
    }

    /** 
     * Construct a BlackJack object with a deck.
     */
    public function testCreateBlackJackWithDeck()
    {
        $blackJack = new BlackJack();
        $this->assertInstanceOf("\App\Game\BlackJack", $blackJack);

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $deck = $blackJack->getDeck();
        $this->assertEquals(52, $deck->getNumberCards());
    }

    /** 
     * Construct a BlackJack object with a deck, and remove a card.
     */
    public function testCreateBlackJackRemoveCard()
    {
        $blackJack = new BlackJack();
        $this->assertInstanceOf("\App\Game\BlackJack", $blackJack);

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $deck = $blackJack->getDeck();
        $this->assertEquals(52, $deck->getNumberCards());

        $removedCards = ["5_of_hearts"];
        $blackJack->setDeck($removedCards);

        $deck = $blackJack->getDeck();
        $this->assertEquals(51, $deck->getNumberCards());
    }

    /** 
     * Construct a BlackJack object with a deck, and shuffle it.
     */
    public function testCreateBlackJackShuffel()
    {
        $blackJack = new BlackJack();
        $this->assertInstanceOf("\App\Game\BlackJack", $blackJack);

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $deckBeforeShuffle = $blackJack->getDeck()->getCards();

        $blackJack->shuffleDeck();

        $deckAfterShuffle = $blackJack->getDeck()->getCards();
        $this->assertNotEquals($deckBeforeShuffle, $deckAfterShuffle);
    }

    /** 
     * Seting the player hand in the BlackJack object, and drawing another card.
     */
    public function testCreateSetPlayerHand()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = [];
        $playerCards[] = $blackJack->playerDraw();
        $blackJack->setPlayer($playerCards);

        $playerHand = $blackJack->getPlayer();

        $this->assertEquals($playerCards, $playerHand);

        $playerCards[] = $blackJack->playerDraw();
        $blackJack->setPlayer($playerCards);

        $playerHand = $blackJack->getPlayer();

        $this->assertEquals($playerCards, $playerHand);
    }

    /** 
     * Seting the house hand in the BlackJack object.
     */
    public function testCreateSetHouseHand()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $houseCards = $blackJack->houseDraw();
        $blackJack->setHouse($houseCards);

        $houseHand = $blackJack->getHouse();

        $this->assertEquals($houseCards, $houseHand);
    }

    /** 
     * Seting the house hand and the player hand.
     */
    public function testCreateSetHouseAndPlayerHand()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["king_of_spades", "8_of_clubs"];
        $blackJack->setPlayer($playerCards);

        $playerHand = $blackJack->getPlayer();

        $this->assertEquals($playerCards, $playerHand);

        $houseCards = $blackJack->houseDraw();
        $blackJack->setHouse($houseCards);

        $houseHand = $blackJack->getHouse();

        $this->assertEquals($houseCards, $houseHand);
    }

    /** 
     * Seting player hand with an ace in hand.
     */
    public function testCreateSetPlayerHandWithAce()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["king_of_spades", "8_of_clubs", "ace_of_hearts"];
        $blackJack->setPlayer($playerCards);

        $playerPoints = $blackJack->playerPoints();
        $this->assertEquals(19, $playerPoints);

        $playerCards = ["king_of_spades", "8_of_clubs", "5_of_diamonds"];
        $blackJack->setPlayer($playerCards);

        $playerPoints = $blackJack->playerPoints();
        $this->assertEquals(23, $playerPoints);
    }

    /** 
     * Seting house hand with an ace in hand.
     */
    public function testCreateSetHouseHandWithAce()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $houseCards = ["queen_of_clubs", "9_of_hearts", "ace_of_spades"];
        $blackJack->setHouse($houseCards);

        $playerPoints = $blackJack->housePoints();
        $this->assertEquals(20, $playerPoints);

        $houseCards = ["queen_of_clubs", "9_of_hearts", "5_of_diamonds"];
        $blackJack->setHouse($houseCards);

        $playerPoints = $blackJack->housePoints();
        $this->assertEquals(24, $playerPoints);
    }

    /** 
     * Game ends in a tie.
     */
    public function testCreateWinningMessageTie()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["king_of_spades", "8_of_clubs", "ace_of_hearts"];
        $blackJack->setPlayer($playerCards);

        $houseCards = ["queen_of_clubs", "9_of_hearts"];
        $blackJack->setHouse($houseCards);

        $this->assertEquals("Det blev oavgjort!", $blackJack->winner());
    }

    /** 
     * Game ends with player winning beacuse dealer has more than 21 points.
     */
    public function testCreateWinningMessageDealerBust()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["king_of_spades", "8_of_clubs", "ace_of_hearts"];
        $blackJack->setPlayer($playerCards);

        $houseCards = ["queen_of_clubs", "9_of_hearts", "8_of_hearts"];
        $blackJack->setHouse($houseCards);

        $this->assertEquals("Du vann! Dealern har över 21 poäng.", $blackJack->winner());
    }

    /** 
     * Game ends with dealer winning beacuse player has more than 21 points.
     */
    public function testCreateWinningMessagePlayerBust()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["queen_of_clubs", "9_of_hearts", "8_of_hearts"];
        $blackJack->setPlayer($playerCards);

        $houseCards = ["king_of_spades", "8_of_clubs", "ace_of_hearts"];
        $blackJack->setHouse($houseCards);

        $this->assertEquals("Dealern vinner! Du har över 21 poäng.", $blackJack->winner());
    }

    /** 
     * Game ends with player winning.
     */
    public function testCreateWinningMessagePlayerWinning()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["queen_of_clubs", "9_of_hearts", "ace_of_hearts"];
        $blackJack->setPlayer($playerCards);

        $houseCards = ["king_of_spades", "8_of_clubs"];
        $blackJack->setHouse($houseCards);

        $this->assertEquals("Du vann!", $blackJack->winner());
    }

    /** 
     * Game ends with dealer winning.
     */
    public function testCreateWinningMessageDealerWinning()
    {
        $blackJack = new BlackJack();

        $removedCards = [];
        $blackJack->setDeck($removedCards);

        $playerCards = ["king_of_spades", "8_of_clubs"];
        $blackJack->setPlayer($playerCards);

        $houseCards = ["queen_of_clubs", "9_of_hearts", "ace_of_hearts"];
        $blackJack->setHouse($houseCards);

        $this->assertEquals("Dealern vinner!", $blackJack->winner());
    }
}
