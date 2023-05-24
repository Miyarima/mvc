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
        $house = $this->houseRepository->findAll();

        $allItems = [["While looking through your little hut, you find theses items"]];
        foreach ($house as $item) {
            $type = $item->getType();
            if ($type === "sword" || $type === "quest") {
                $allItems[] = [$item->getName()];
            }
        }

        $allItems[] = ["As you continue exploring, you notice a door to the north"];

        return $allItems;
    }

    /**
     * Returns all House entries which you can pickup.
     */
    public function getHousePickups(): array
    {
        $house = $this->houseRepository->findAll();

        $allItems = [];
        foreach ($house as $item) {
            $type = $item->getType();
            if ($type === "sword" || $type === "quest") {
                $allItems[] = [$item->getName(),  $item->getType(), $item->getContent()];
            }
        }

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

    public function getMessage()
    {
        $messages = $this->houseRepository->findBy(['type' => 'message']);
        $visit =  $this->houseRepository->findOneBy(['name' => 'visit']);

        $message = "";

        foreach ($messages as $item) {
            if ($visit->getContent() === "0") {
                if ($item->getName() === "first message") {
                    $message = $item->getContent();
                    $this->updateHouseEntry(["visit", "counter", "1"]);
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
     * When called this function resets the house table in the database.
    */
    public function resetHouse(): void
    {
        $arrays = [
            ["visit", "counter", "0"],
            ["first message", "message", "After dreaming of my blacksmith grandfather, I inherited his legacy. With honor and responsibility, I embarked on my own adventure, forging a path filled with challenges and surprises. Each hammer strike honored his unique knowledge."],
            ["repeated message", "message", "Once again, you find yourself inside your comfortable home, where you can gaze through your window and behold the breathtaking beauty of the fields."],
            ["quest", "quest", "Equip yourself! at:15, hp:20. Venture forth, defeat the dungeon boss, and seize the treasure chest. Glory and riches await your triumph!"],
            ["sword", "sword", "A Honed Claymor made by the Legendary Grandfather Smith"]
        ];

        $this->houseRepository->deleteAllRows();
        foreach ($arrays as $array) {
            $this->createHouseEntry($array);
        }
    }
}
