<?php

namespace App\Adventure;

use App\Adventure\Inventory;

use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;

class Game
{   
    private Inventory $inventory;
    private ManagerRegistry $doctrine;
    private PlayerRepository $playerRepository;

    public  function __construct(
        ManagerRegistry $doctrine,
        PlayerRepository $playerRepository
    )
    {
        $this->doctrine = $doctrine;
        $this->playerRepository = $playerRepository;
        $this->inventory = new Inventory($doctrine, $playerRepository);
    }

    /** 
     * Extracts the action from $command and calls the appropriate method
     */
    public function command(string $command, string $pos): array
    {
        $actions = explode(" ", $command);

        if($actions[0] == "go") {
            return $this->move($actions[1], $pos);
        } elseif ($actions[0] == "help") {
            return $this->help();
        } elseif ($actions[0] == "inventory") {
            return $this->inventory();
        }

        return [
            "error",
            ["I'm not familiar with your usage of '$command'"]
        ];
    }
    
    public function addToInventory(array $item): void
    {
        $this->inventory->createInventoryEntry($item);
    }

    public function inventory(): array
    {
        return ["inventory", $this->inventory->getInventoryEntries()];
    }

    /** 
     * Depending on the $pos, it will call the appropriate method
    */
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

    /** 
     * If the $pos is either house or path, you can go north
     */
    public function moveNorth(string $pos): array
    {
        if ($pos == "house") {
            return ['go', 'path_adventure'];
        } elseif ($pos == "path") {
            return ['go', 'dungeon_adventure'];
        }

        return ["go", "You can't"];
    }

    /** 
     * If the $pos is either dungeon or path, you can go south
     */
    public function moveSouth(string $pos): array
    {
        if ($pos == "path") {
            return ['go', 'start_adventure'];
        } elseif ($pos == "dungeon") {
            return ['go', 'path_adventure'];
        }

        return ["go", "You can't"];
    }

    /** 
     * If the $pos is path, you can go west
     */
    public function moveWest(string $pos): array
    {
        if ($pos == "path") {
            return ['go', 'cave_adventure'];
        }

        return ["go", "You can't"];
    }

    /** 
     * If the $pos is cave, you can go east
     */
    public function moveEast(string $pos): array
    {
        if ($pos == "cave") {
            return ['go', 'path_adventure'];
        }

        return ["go", "You can't"];
    }

    /** 
     * If command is 'help', it will return the help array
     */
    public function help(): array 
    {
        return [
            "help",
            [
            "Theses are the things you can do:",
            "Use the 'go' command to move in different directions e.g south.",
            "Inventory",
            "Pickup",
            "Help"
            ]
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
