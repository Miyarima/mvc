<?php

namespace App\Library;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;

use RuntimeException;

class Book
{
    private Library $book;
    private ManagerRegistry $doctrine;
    private LibraryRepository $libraryRepository;

    public function __construct(
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository
    ) {
        $this->doctrine = $doctrine;
        $this->libraryRepository = $libraryRepository;
    }

    /**
     * Creates a new Library object.
     */
    public function createBook(array $data): void
    {
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

    /**
     * Updates Library object in database.
     */
    public function updateBook(array $data): void
    {
        try {
            $book = $this->libraryRepository->find($data['id']);

            if (!$book) {
                throw new RuntimeException('Book not found');
            }

            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setIsbn($data['ISBN']);
            $book->setImgLink($data['img']);

            $this->libraryRepository->save($book, true);
        } catch (RuntimeException $e) {
            echo 'An error occurred: ' . $e->getMessage();
        }
    }

    /**
     * Delete Library object in database.
     */
    public function deleteBook(
        string $bookId
    ): void {
        try {
            $book = $this->libraryRepository->find($bookId);

            if (!$book) {
                throw new RuntimeException('Book not found');
            }

            $this->libraryRepository->remove($book, true);
        } catch (RuntimeException $e) {
            echo 'An error occurred: ' . $e->getMessage();
        }
    }
}
