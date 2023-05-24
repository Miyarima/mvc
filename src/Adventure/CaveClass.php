<?php

namespace App\Adventure;

use App\Entity\Cave;
use App\Repository\CaveRepository;
use Doctrine\Persistence\ManagerRegistry;

class CaveClass
{
    private ManagerRegistry $doctrine;
    private CaveRepository $repository;

    public function __construct(
        ManagerRegistry $doctrine,
        CaveRepository $repository
    ) {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }

    /**
     * Creates a new Cave entry.
     */
    public function createCaveEntry(array $data): void
    {
        $item = new Cave();

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->addCaveEntry($item);
    }

    /**
     * Adds Cave entry to database.
    */
    public function addCaveEntry(Cave $item): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($item);
        $entityManager->flush();
    }

    /**
     * Returns all Cave entries.
     */
    public function getCaveEntries(): array
    {
        $Cave = $this->repository->findAll();

        $allItems = [];
        foreach ($Cave as $item) {
            $type = $item->getType();
            if ($type === "info") {
                $allItems[] = [$item->getContent()];
            }
        }

        $allItems[] = ["To return to the path, it also says to go east."];

        return $allItems;
    }

    /**
     * Removes an item from the Cave
     */
    public function removeCaveEntry(string $name): void
    {
        $item =  $this->repository->findOneBy(['name' => $name]);
        $this->repository->remove($item, true);
    }

    /**
     * Updates an item in the Cave
     */
    public function updateCaveEntry(array $data): void
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
                    $this->updateCaveEntry(["visit", "counter", "1"]);
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
