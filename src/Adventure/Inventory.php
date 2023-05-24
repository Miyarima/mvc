<?php

namespace App\Adventure;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;

class Inventory
{
    private ManagerRegistry $doctrine;
    private PlayerRepository $playerRepository;

    public function __construct(
        ManagerRegistry $doctrine,
        PlayerRepository $playerRepository
    ) {
        $this->doctrine = $doctrine;
        $this->playerRepository = $playerRepository;
    }

    /**
     * Creates a new inventory entry.
     */
    public function createInventoryEntry(array $data): void
    {
        $item = new Player();

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->addInventoryEntry($item);
    }

    /**
     * Adds inventory entry to player.
    */
    public function addInventoryEntry(Player $item): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($item);
        $entityManager->flush();
    }

    /**
     * Returns all inventory entries.
     */
    public function getInventoryEntries(): array
    {
        $inventory = $this->playerRepository->findAll();

        $allItems = [];
        foreach ($inventory as $item) {
            $allItems[] = [$item->getName(), " | ",$item->getContent()];
        }

        return $allItems;
    }

    /**
     * Returns all inventory entries.
     */
    public function getInventoryStats(): array
    {
        $inventory = $this->playerRepository->findAll();

        $allItems = [];
        foreach ($inventory as $item) {
            if ($item->getType() == "stat") {
                $allItems[] = [$item->getName(), $item->getContent()];
            }
        }

        return $allItems;
    }

    /**
     * Removes an item from the inventory
     */
    public function removeInventoryEntry(string $name): void
    {
        $item =  $this->playerRepository->findOneBy(['name' => $name]);
        $this->playerRepository->remove($item, true);
    }

    /**
     * Updates an item in the inventory
     */
    public function updateInventoryEntry(array $data): void
    {
        $item =  $this->playerRepository->findOneBy(['name' => $data[0]]);

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->playerRepository->save($item, true);
    }

    /**
     * When called this function resets the player table in the database.
    */
    public function resetInventory(): void
    {
        $stats = [["attack", "stat", "0"], ["health points", "stat", "10"]];

        $this->playerRepository->deleteAllRows();
        foreach ($stats as $stat) {
            $this->createInventoryEntry($stat);
        }
    }
}
