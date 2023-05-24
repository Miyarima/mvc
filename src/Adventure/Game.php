<?php

namespace App\Adventure;

use App\Adventure\Inventory;

use App\Repository\PlayerRepository;
use App\Repository\HouseRepository;
use App\Repository\PathRepository;
use App\Repository\CaveRepository;
use App\Repository\DungeonRepository;
use Doctrine\Persistence\ManagerRegistry;

class Game
{
    private Inventory $inventory;
    private HouseClass $house;
    private PathClass $path;
    private CaveClass $cave;
    private DungeonClass $dungeon;

    public function __construct(
        ManagerRegistry $doctrine,
        PlayerRepository $playerRepository,
        HouseRepository $houseRepository,
        PathRepository $pathRepository,
        CaveRepository $caveRepository,
        DungeonRepository $dungeonRepository
    ) {
        $this->inventory = new Inventory($doctrine, $playerRepository);
        $this->house = new HouseClass($doctrine, $houseRepository);
        $this->path = new PathClass($doctrine, $pathRepository);
        $this->cave = new CaveClass($doctrine, $caveRepository);
        $this->dungeon = new DungeonClass($doctrine, $dungeonRepository);
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
        } elseif ($actions[0] == "look") {
            return $this->look($pos);
        } elseif ($actions[0] == "pickup") {
            return $this->pickup($actions[1], $pos);
        } elseif ($actions[0] == "train") {
            return $this->train($pos);
        } elseif ($actions[0] == "kill") {
            return $this->kill($pos);
        }

        return [
            "error",
            ["I'm not familiar with your usage of '$command'"]
        ];
    }

    /** 
     * Check if the postion is a valid location for kill
    */
    public function kill(string $pos): array
    {   
        if ($pos === "dungeon") {
            $stats = $this->inventory->getInventoryStats();
            $boss = $this->dungeon->getDungeonBoss();
            $monster = $boss[0][0];

            if ($boss[0][1] === "0") {
                return ["train", ["$monster, has already been defeated."]];
            }

            if (intval($stats[0][1]) > 14) {
                $this->updateDungeon([$boss[0][0], "boss", "0"]);
    
                return ["train", ["You killed $monster, and claimed the treasure for yourself, containing the honor of this incredible achievement."]];
            }
            return ["train", ["$monster, proved too powerful for you, and you fled like the coward you believed yourself to be"]];
        }

        return ["train", ["To use the train, you must be inside the caves."]];
    }

    /** 
     * Check if the postion is a valid location for train
    */
    public function train(string $pos): array
    {   
        if ($pos === "cave") {
            $monster = [
                "Venomous Shadowcrawler",
                "Cursed Soulseeker",
                "Fiery Scorchbeast",
                "Dreadful Bonecrusher",
                "Electric Stormfiend",
                "Frostbite Yeti",
                "Blazing Infernodemon",
                "Plague-infested Swarmguard",
                "Vengeful Spiritwraith",
                "Corrupted Bloodreaver",
            ];

            $stats = $this->inventory->getInventoryStats();

            $at = intval($stats[0][1]) + 1;
            $hp = intval($stats[1][1]) + 2;

            $this->updateInventory([$stats[0][0], "stat", "$at"]);
            $this->updateInventory([$stats[1][0], "stat", "$hp"]);

            $rand = rand(0, 9);

            return ["train", ["You killed a $monster[$rand], your attack is now $at and your health is $hp"]];
        }

        return ["train", ["To use the train, you must be inside the caves."]];
    }

    /** 
     * Check if the item is a valid pickup and calls the appropriate method
    */
    public function pickup(string $item, string $pos): array
    {
        if ($pos === "house") {
            if ($item === "sword") {
                return ["pickup", [$this->pickupItem("sword")]];
            } elseif ($item === "quest") {
                return ["pickup", [$this->pickupItem("quest")]];
            } else {
                return ["pickup", ["There is no item named $item"]];
            }
        }
    
        return ["pickup", ["To use 'pickup,' you must be inside the house."]];
    }

    public function pickupItem($item): string
    {   
        $items = $this->house->getHousePickups();
        $wholeItem = [];
        foreach ($items as $i) {
            if ($i[1] === $item) {
                $wholeItem = $i;
            }
        }

        $answer = "";
        if (!empty($wholeItem)) {
            $this->addToInventory($wholeItem);
            $this->removeFromHouse($wholeItem[0]);
            if ($item === "sword") {
                $stats = $this->inventory->getInventoryStats();
                $at = intval($stats[0][1]) + 10;
                $this->updateInventory([$stats[0][0], "stat", $at]);
                $answer = "You picked up the $item, and 10 attack has been added";
            } else {
                $answer = "You picked up the $item";
            }
        } else {
            $answer = "There is no $item to be picked up";
        }

        return $answer;
    }

    /**
     * Depending on the $pos, it will call the appropriate method
    */
    public function look(string $pos): array
    {
        if($pos === "house") {
            return $this->house();
        } elseif ($pos === "path") {
            return $this->path();
        } elseif ($pos === "cave") {
            return $this->cave();
        } elseif ($pos === "dungeon") {
            return $this->dungeon();
        }

        return ["go", "You can't"];
    }

    /**
     * Returns the message for the given position
    */
    public function message(string $pos): string
    {
        if ($pos === "house") {
            return $this->house->getMessage();
        } elseif ($pos === "path") {
            return $this->path->getMessage();
        } elseif ($pos === "cave") {
            return $this->cave->getMessage();
        } elseif ($pos === "dungeon") {
            return $this->dungeon->getMessage();
        }
    }

    /**
     * Returns all items in the dungeon
    */
    public function dungeon(): array
    {
        return ["dungeon", $this->dungeon->getDungeonEntries()];
    }

    /**
     * Sends an item to be added to the dungeon
     * @param array<string> $item
    */
    public function addToDungeon(array $item): void
    {
        $this->dungeon->createDungeonEntry($item);
    }

    /**
     * Sends an item to be removed from the dungeon
    */
    public function removeFromDungeon(string $item): void
    {
        $this->dungeon->removeDungeonEntry($item);
    }

    /**
     * Sends an item to be update in the dungeon
     * @param array<string> $item
    */
    public function updateDungeon(array $item): void
    {
        $this->dungeon->updateDungeonEntry($item);
    }

    /**
     * Returns all items in the cave
    */
    public function cave(): array
    {
        return ["cave", $this->cave->getCaveEntries()];
    }

    /**
     * Sends an item to be added to the cave
     * @param array<string> $item
    */
    public function addToCave(array $item): void
    {
        $this->cave->createCaveEntry($item);
    }

    /**
     * Sends an item to be removed from the cave
    */
    public function removeFromCave(string $item): void
    {
        $this->cave->removeCaveEntry($item);
    }

    /**
     * Sends an item to be update in the cave
     * @param array<string> $item
    */
    public function updateCave(array $item): void
    {
        $this->cave->updateCaveEntry($item);
    }

    /**
     * Returns all items in the path
    */
    public function path(): array
    {
        return ["path", $this->path->getPathEntries()];
    }

    /**
     * Sends an item to be added to the path
     * @param array<string> $item
    */
    public function addToPath(array $item): void
    {
        $this->path->createPathEntry($item);
    }

    /**
     * Sends an item to be removed from the path
    */
    public function removeFromPath(string $item): void
    {
        $this->path->removePathEntry($item);
    }

    /**
     * Sends an item to be update in the path
     * @param array<string> $item
    */
    public function updatePath(array $item): void
    {
        $this->path->updatePathEntry($item);
    }

    /**
     * Returns all items in the house
    */
    public function house(): array
    {
        return ["house", $this->house->getHouseEntries()];
    }

    /**
     * Sends an item to be added to the house
     * @param array<string> $item
    */
    public function addToHouse(array $item): void
    {
        $this->house->createHouseEntry($item);
    }

    /**
     * Sends an item to be removed from the house
    */
    public function removeFromHouse(string $item): void
    {
        $this->house->removeHouseEntry($item);
    }

    /**
     * Sends an item to be update in the house
     * @param array<string> $item
    */
    public function updateHouse(array $item): void
    {
        $this->house->updateHouseEntry($item);
    }

    /**
     * Returns all items in the inventory
    */
    public function inventory(): array
    {
        return ["inventory", $this->inventory->getInventoryEntries()];
    }

    /**
     * Sends an item to be added to the inventory
     * @param array<string> $item
    */
    public function addToInventory(array $item): void
    {
        $this->inventory->createInventoryEntry($item);
    }

    /**
     * Sends an item to be removed from the inventory
    */
    public function removeFromInventory(string $item): void
    {
        $this->inventory->removeInventoryEntry($item);
    }

    /**
     * Sends an item to be update in the inventory
     * @param array<string> $item
    */
    public function updateInventory(array $item): void
    {
        $this->inventory->updateInventoryEntry($item);
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
            return ['go', 'house_adventure'];
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

    public function resetGame(): void
    {
        $this->inventory->resetInventory();
        $this->house->resetHouse();
        $this->path->resetPath();
        $this->cave->resetCave();
        $this->dungeon->resetDungeon();
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
            "Use 'look' to see what's around you.",
            "Inventory",
            "Pickup",
            "Train",
            "Help"
            ]
        ];
    }
}
