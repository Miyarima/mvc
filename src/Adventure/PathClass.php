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
}
