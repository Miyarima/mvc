<?php

namespace App\Adventure;

// use App\Deck\Card;
// use App\Deck\CardHand;
// use App\Deck\DeckOfCards;

class Game
{
    // private CardHand $playerHand;
    // private CardHand $houseHand;
    // private DeckOfCards $deck;

    /**
    * @var array<string, int> $values
    */
    // private array $values = [
    //     "2" => 2,
    //     "3" => 3,
    //     "4" => 4,
    //     "5" => 5,
    //     "6" => 6,
    //     "7" => 7,
    //     "8" => 8,
    //     "9" => 9,
    //     "10" => 10,
    //     "jack" => 10,
    //     "queen" => 10,
    //     "king" => 10,
    //     "ace" => 11
    // ];

    // public function __construct()
    // {
    //     $this->playerHand = new CardHand();
    //     $this->houseHand = new CardHand();
    //     $this->deck = new DeckOfCards();
    // }
    public function command(string $command, string $pos): array
    {
        $actions = explode(" ", $command);

        if($actions[0] == "go") {
            return $this->move($actions[1], $pos);
        }

        return [
            "error",
            "I'm not familiar with your usage of '$command'"
        ];
    }
    
    public function move(string $direction, string $pos): array
    {
        if ($direction == "north") {
            return $this->moveNorth($pos);
        } elseif ($direction == "south") {
            return $this->moveSouth($pos);
        } elseif ($direction == "east") {
            return $this->moveEast($pos);
        } elseif ($direction == "west") {
            return $this->moveWest($pos);
        }

        return ["go", "You can't"];
    }

    public function moveNorth(string $pos): array
    {
        if ($pos == "house") {
            return ['go', 'path_adventure'];
        } elseif ($pos == "path") {
            return ['go', 'dungeon_adventure'];
        }

        return ["go", "You can't"];
    }

    public function moveSouth(string $pos): array
    {
        if ($pos == "path") {
            return ['go', 'start_adventure'];
        } elseif ($pos == "dungeon") {
            return ['go', 'path_adventure'];
        }

        return ["go", "You can't"];
    }

    public function moveWest(string $pos): array
    {
        if ($pos == "path") {
            return ['go', 'cave_adventure'];
        }

        return ["go", "You can't"];
    }

    public function moveEast(string $pos): array
    {
        if ($pos == "cave") {
            return ['go', 'path_adventure'];
        }

        return ["go", "You can't"];
    }

    public function help(): array 
    {
        return [
            "Theses are the things you can do:",
            "Use the 'go' command to move in different directions e.g south.",
            "Inventory",
            "Pickup",
            "Help"
        ];
    }

    /**
     * Sets the deck, and removes cards that has been drawn.
     * @param array<string> $removed
     */
    // public function setDeck(array $removed): void
    // {
    //     $this->deck =  new DeckOfCards();
    //     for ($i = 1; $i <= 52; $i++) {
    //         $this->deck->add(new Card());
    //     }

    //     if($removed !== []) {
    //         foreach($removed as $remove) {
    //             $this->deck->removeCard($remove);
    //         }
    //     }
    // }

    /**
     * Returns the deck.
     */
    // public function getDeck(): DeckOfCards
    // {
    //     return $this->deck;
    // }

    /**
     * Shuffles the deck.
     */
    // public function shuffleDeck(): void
    // {
    //     if ($this->deck->getNumberCards() > 51) {
    //         $this->deck->shuffleDeck();
    //     }
    // }
}
