<?php

namespace App\Adventure;

use App\Adventure\Inventory;

use App\Repository\PlayerRepository;
use App\Repository\HouseRepository;
use App\Repository\PathRepository;
use Doctrine\Persistence\ManagerRegistry;

class Game
{
    private Inventory $inventory;
    private HouseClass $house;
    private PathClass $path;

    public function __construct(
        ManagerRegistry $doctrine,
        PlayerRepository $playerRepository,
        HouseRepository $houseRepository,
        PathRepository $pathRepository
    ) {
        $this->inventory = new Inventory($doctrine, $playerRepository);
        $this->house = new HouseClass($doctrine, $houseRepository);
        $this->path = new PathClass($doctrine, $pathRepository);
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
        }

        return [
            "error",
            ["I'm not familiar with your usage of '$command'"]
        ];
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
        }

        return ["go", "You can't"];
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
            "Help"
            ]
        ];
    }
}
