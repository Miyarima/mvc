<?php

namespace App\Library;

use App\Entity\Library;

use Doctrine\Persistence\ManagerRegistry;

class Book
{
    private $book;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Creates a new Library object.
     */
    public function createBook(
        array $data
    ): void {
        $book = new Library();

        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setImgLink($data['img']);
        $book->setIsbn($data['ISBN']);

        $this->book = $book;
    }

    /**
     * Adds Library object to database.
     */
    public function addNewBook(): void
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($this->book);
        $entityManager->flush();
    }
}
