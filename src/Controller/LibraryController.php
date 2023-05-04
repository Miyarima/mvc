<?php

namespace App\Controller;

use App\Entity\Library;
use App\Library\Book;
use App\Repository\LibraryRepository;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/form', name: 'form_book', methods: ['GET'])]
    public function addForm(): Response
    {
        return $this->render('library/form.html.twig', [
            "addBookUrl" => $this->generateUrl('add_book'),
        ]);
    }

    #[Route('/library/add/book', name: 'add_book', methods: ['POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository,
        Request $request
    ): Response {
        $book = new Book($doctrine, $libraryRepository);
        $form = [];
        foreach ($request->request as $key => $value) {
            $form[$key] = $value;
        }
        $book->createBook($form);
        $book->addNewBook();

        return $this->render('library/event.html.twig', [
            "text" => "tillagd",
            "title" => $form['title'],
            "author" => $form['author'],
            "img" => $form['img'],
            "isbn" => $form['ISBN'],
        ]);
    }

    #[Route('/library/show', name: 'library_show_all', methods: ['GET'])]
    public function showAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();

        $allBooks = [];
        foreach ($books as $book) {
            $allBooks[] = [$book->getImgLink(), $book->getId()];
        }

        return $this->render('library/all_books.html.twig', [
            "books" => $allBooks,
        ]);
    }

    #[Route('/library/show/{id}', name: 'show_book_by_id', methods: ['GET'])]
    public function showBookByID(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository
            ->find($id);

        return $this->render('library/single_book.html.twig', [
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "img" => $book->getImgLink(),
            "isbn" => $book->getIsbn(),
            "id" => $book->getId(),
        ]);
    }

    #[Route('/library/delete/book/{id}', name: 'delete_book', methods: ['GET'])]
    public function deleteBook(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        return $this->render('library/delete_form.html.twig', [
            "deleteBookUrl" => $this->generateUrl('delete_book_done'),
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "img" => $book->getImgLink(),
            "isbn" => $book->getIsbn(),
            "id" => $book->getId(),
        ]);
    }

    #[Route('/library/delete/book', name: 'delete_book_done', methods: ['POST'])]
    public function deleteBookDone(
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository,
        Request $request
    ): Response {
        $book = new Book($doctrine, $libraryRepository);
        $form = [];
        foreach ($request->request as $key => $value) {
            $form[$key] = $value;
        }
        $book->deleteBook($form['id']);

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/update/book/{id}', name: 'update_book', methods: ['GET'])]
    public function updateBook(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        return $this->render('library/update_form.html.twig', [
            "updateBookUrl" => $this->generateUrl('update_book_done'),
            "title" => $book->getTitle(),
            "author" => $book->getAuthor(),
            "img" => $book->getImgLink(),
            "isbn" => $book->getIsbn(),
            "id" => $book->getId(),
        ]);
    }

    #[Route('/library/update/book', name: 'update_book_done', methods: ['POST'])]
    public function updateBookDone(
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository,
        Request $request
    ): Response {
        $book = new Book($doctrine, $libraryRepository);
        $form = [];
        foreach ($request->request as $key => $value) {
            $form[$key] = $value;
        }
        $book->updateBook($form);

        return $this->render('library/event.html.twig', [
            "text" => "uppdaterad",
            "title" => $form['title'],
            "author" => $form['author'],
            "img" => $form['img'],
            "isbn" => $form['ISBN'],
        ]);
    }
}
