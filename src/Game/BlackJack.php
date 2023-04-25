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
     * Sets the deck, and removes cards that has been drawn.
     * @param array<string> $removed
     */
    public function setDeck(array $removed): void
    {
        $this->deck =  new DeckOfCards();
        for ($i = 1; $i <= 52; $i++) {
            $this->deck->add(new Card());
        }

        if($removed !== []) {
            foreach($removed as $remove) {
                $this->deck->removeCard($remove);
            }
        }
    }

    /** 
     * Returns the deck.
     */
    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    /** 
     * Shuffles the deck.
     */
    public function shuffleDeck(): void
    {
        if ($this->deck->getNumberCards() > 51) {
            $this->deck->shuffleDeck();
        }
    }

    /**
     * Creates a new CardHand and adds all player cards to it.
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
     * Creates a new CardHand and adds all house cards to it.
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
     * Returns an array of all the cards the player has drawn.
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
     * Returns an array of all the cards the house has drawn.
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

    /** 
     * Calculates the points of the player hand.
     */
    public function playerPoints(): int
    {
        $total = 0;
        foreach ($this->playerHand->getCards() as $card) {
            $val = explode("_", $card->getName());
            $total += $this->values[$val[0]];
            if ($total > 21 and $this->aceInHandPlayer()) {
                $total -= 10;
            }
        }
        return $total;
    }

    /**
     * Calculates the points of the house hand.
     */
    public function housePoints(): int
    {
        $total = 0;
        foreach ($this->houseHand->getCards() as $card) {
            $val = explode("_", $card->getName());
            $total += $this->values[$val[0]];
            if ($total > 21 and $this->aceInHandHouse()) {
                $total -= 10;
            }
        }

        return $total;
    }

    /**
     * Returns an array of cards that are drawn.
     * @return array<string>
     */
    public function houseDraw(): array
    {
        $stop = 17;
        if ($this->playerPoints() > 17 and $this->playerPoints() < 22) {
            $stop = $this->playerPoints();
        }

        $cards = [];
        $points = 0;
        while ($stop > $points) {
            $randInt = random_int(0, $this->deck->getNumberCards()-1);
            $card = $this->deck->getSpecificCard($randInt)->getName();
            $this->deck->removeCard($card);
            $cards[] = $card;
            $this->setHouse($cards);
            $points = $this->housePoints();
            if ($this->housePoints() > 21 and $this->aceInHandHouse()) {
                $points -= 10;
            }
        }

        return $cards;
    }

    /**
     * Returns the name of the card that the player has drawn.
     * @return string
     */
    public function playerDraw(): string
    {
        $randInt = random_int(0, $this->deck->getNumberCards()-1);
        $card = $this->deck->getSpecificCard($randInt)->getName();
        $this->deck->removeCard($card);

        return $card;
    }

    /**
     * Calculates the winner of the game.
     */
    public function winner(): string
    {
        $message = "Det blev oavgjort!";
        if ($this->housePoints() > 21) {
            $message = "Du vann! Dealern har över 21 poäng.";
        } elseif ($this->playerPoints() > 21) {
            $message = "Dealern vinner! Du har över 21 poäng.";
        } elseif ($this->playerPoints() > $this->housePoints()) {
            $message = "Du vann!";
        } elseif ($this->housePoints() > $this->playerPoints()) {
            $message = "Dealern vinner!";
        }

        return $message;
    }

    /** 
     * Runs through the house hand and checks if there is an ace.
     */
    public function aceInHandHouse(): bool
    {
        foreach ($this->houseHand->getCards() as $card) {
            $val = explode("_", $card->getName());
            if ($val[0] === "ace") {
                return true;
            }
        }
        return false;
    }

    /**
     * Runs through the player hand and checks if there is an ace.
     */
    public function aceInHandPlayer(): bool
    {
        foreach ($this->playerHand->getCards() as $card) {
            $val = explode("_", $card->getName());
            if ($val[0] === "ace") {
                return true;
            }
        }
        return false;
    }
}
