<?php

namespace App\Adventure;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;

class Inventory
{
    private Player $player;
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
     * Updates Library object in database.
     */
    // public function updateBook(array $data): void
    // {
    //     try {
    //         $book = $this->libraryRepository->find($data['id']);

    //         if (!$book) {
    //             throw new RuntimeException('Book not found');
    //         }

    //         $book->setTitle($data['title']);
    //         $book->setAuthor($data['author']);
    //         $book->setIsbn($data['ISBN']);
    //         $book->setImgLink($data['img']);

    //         $this->libraryRepository->save($book, true);
    //     } catch (RuntimeException $e) {
    //         echo 'An error occurred: ' . $e->getMessage();
    //     }
    // }

    /**
     * Delete Library object in database.
     */
    // public function deleteBook(
    //     string $bookId
    // ): void {
    //     try {
    //         $book = $this->libraryRepository->find($bookId);

    //         if (!$book) {
    //             throw new RuntimeException('Book not found');
    //         }

    //         $this->libraryRepository->remove($book, true);
    //     } catch (RuntimeException $e) {
    //         echo 'An error occurred: ' . $e->getMessage();
    //     }
    // }
}
