<?php

namespace App\Adventure;

use App\Entity\House;
use App\Repository\HouseRepository;
use Doctrine\Persistence\ManagerRegistry;

class HouseClass
{
    private ManagerRegistry $doctrine;
    private HouseRepository $houseRepository;

    public function __construct(
        ManagerRegistry $doctrine,
        HouseRepository $houseRepository
    ) {
        $this->doctrine = $doctrine;
        $this->houseRepository = $houseRepository;
    }

    /**
     * Creates a new House entry.
     */
    public function createHouseEntry(array $data): void
    {
        $item = new House();

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->addHouseEntry($item);
    }

    /**
     * Adds House entry to database.
    */
    public function addHouseEntry(House $item): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($item);
        $entityManager->flush();
    }

    /**
     * Returns all House entries.
     */
    public function getHouseEntries(): array
    {
        $House = $this->houseRepository->findAll();

        $allItems = [["While looking through your little hut, you find theses items"]];
        foreach ($House as $item) {
            $type = $item->getType();
            if ($type === "sword" || $type === "quest") {
                $allItems[] = [$item->getName()];
            }
        }

        $allItems[] = ["As you continue exploring, you notice a door to the north"];

        return $allItems;
    }

    /**
     * Removes an item from the House
     */
    public function removeHouseEntry(string $name): void
    {
        $item =  $this->houseRepository->findOneBy(['name' => $name]);
        $this->houseRepository->remove($item, true);
    }

    /**
     * Updates an item in the House
     */
    public function updateHouseEntry(array $data): void
    {
        $item =  $this->houseRepository->findOneBy(['name' => $data[0]]);

        $item->setName($data[0]);
        $item->setType($data[1]);
        $item->setContent($data[2]);

        $this->houseRepository->save($item, true);
    }
}
