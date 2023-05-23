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
        $p = new Player();

        $p->setName($data[0]);
        $p->setType($data[1]);
        $p->setContent($data[2]);

        $this->addInventoryEntry($p);
    }

    /**
     * Adds inventory entry to player.
    */
    public function addInventoryEntry(player $p): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($p);
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
            $allItems[] = [$item->getName(), $item->getType(), $item->getContent()];
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

    public function updateInventoryEntry(array $data): void
    {
        $item =  $this->playerRepository->findOneBy(['name' => $data[0]]);

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->playerRepository->save($item, true);
    }
}
