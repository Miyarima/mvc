<?php

namespace App\Adventure;

use App\Entity\Dungeon;
use App\Repository\DungeonRepository;
use Doctrine\Persistence\ManagerRegistry;

class DungeonClass
{
    private ManagerRegistry $doctrine;
    private DungeonRepository $repository;

    public function __construct(
        ManagerRegistry $doctrine,
        DungeonRepository $repository
    ) {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }

    /**
     * Creates a new Dungeon entry.
     */
    public function createDungeonEntry(array $data): void
    {
        $item = new Dungeon();

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->addDungeonEntry($item);
    }

    /**
     * Adds Dungeon entry to database.
    */
    public function addDungeonEntry(Dungeon $item): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($item);
        $entityManager->flush();
    }

    /**
     * Returns all Dungeon entries.
     */
    public function getDungeonEntries(): array
    {
        $Dungeon = $this->repository->findAll();

        $allItems = [];
        foreach ($Dungeon as $item) {
            $type = $item->getType();
            if ($type === "info") {
                $allItems[] = [$item->getContent()];
            }
        }

        return $allItems;
    }

    /**
     * Removes an item from the Dungeon
     */
    public function removeDungeonEntry(string $name): void
    {
        $item =  $this->repository->findOneBy(['name' => $name]);
        $this->repository->remove($item, true);
    }

    /**
     * Updates an item in the Dungeon
     */
    public function updateDungeonEntry(array $data): void
    {
        $item =  $this->repository->findOneBy(['name' => $data[0]]);

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->repository->save($item, true);
    }

    /**
     * Returns the message depending on the visit counter.
    */
    public function getMessage()
    {
        $messages = $this->repository->findBy(['type' => 'message']);
        $visit =  $this->repository->findOneBy(['name' => 'visit']);

        $message = "";

        foreach ($messages as $item) {
            if ($visit->getContent() === "0") {
                if ($item->getName() === "first message") {
                    $message = $item->getContent();
                    $this->updateDungeonEntry(["visit", "counter", "1"]);
                    break;
                }
            } elseif ($visit->getContent() === "1") {
                if ($item->getName() === "repeated message") {
                    $message = $item->getContent();
                }
            }
        }

        return $message;
    }
}
