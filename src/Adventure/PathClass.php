<?php

namespace App\Adventure;

use App\Entity\Path;
use App\Repository\PathRepository;
use Doctrine\Persistence\ManagerRegistry;

class PathClass
{
    private ManagerRegistry $doctrine;
    private PathRepository $repository;

    public function __construct(
        ManagerRegistry $doctrine,
        PathRepository $repository
    ) {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }

    /**
     * Creates a new Path entry.
     */
    public function createPathEntry(array $data): void
    {
        $item = new Path();

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->addPathEntry($item);
    }

    /**
     * Adds Path entry to database.
    */
    public function addPathEntry(Path $item): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($item);
        $entityManager->flush();
    }

    /**
     * Returns all Path entries.
     */
    public function getPathEntries(): array
    {
        $path = $this->repository->findAll();

        $allItems = [];
        foreach ($path as $item) {
            $type = $item->getType();
            if ($type === "info") {
                $allItems[] = [$item->getContent()];
            }
        }

        return $allItems;
    }

    /**
     * Removes an item from the Path
     */
    public function removePathEntry(string $name): void
    {
        $item =  $this->repository->findOneBy(['name' => $name]);
        $this->repository->remove($item, true);
    }

    /**
     * Updates an item in the Path
     */
    public function updatePathEntry(array $data): void
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
                    $this->updatePathEntry(["visit", "counter", "1"]);
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

    /** 
     * When called this function resets the path table in the database.
    */
    public function resetPath(): void
    {   
        $arrays = [
            ["visit", "counter", "0"],
            ["first message", "message", "Emotions filled the air as I left, carrying your legacy within. The cottage stood silent, but I felt both weight and excitement. Your knowledge lives on, and I'll honor it through my own adventure."],
            ["repeated message", "message", "Embrace the path anew. May your journey bring forth glorious triumphs and bountiful riches."],
            ["look", "info", "As you encounter a signpost, it directs you towards three distinct paths. Head south to return to the comfort of your home, venture west to explore the enchanting and bountiful caves, or brave the north to confront the perils of the deepest dungeon."],
        ];

        $this->repository->deleteAllRows();
        foreach ($arrays as $array) {
            $this->createPathEntry($array);
        }
    }
}
