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

    #[Route('/library/add/book', name: 'library_show_all', methods: ['POST'])]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $book = new Book($doctrine);
        $form = [];
        foreach ($request->request as $key => $value) {
            $form[$key] = $value;
        }
        $book->createBook($form);
        $book->addNewBook();

        return $this->render('library/book_added.html.twig', [
            "libraryUrl" => $this->generateUrl('library'),
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

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/library/show/{id}', name: 'show_book_by_id', methods: ['GET'])]
    public function showBookByID(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $books = $libraryRepository
            ->find($id);

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/library/delete/book/{id}', name: 'delete_book_by_id', methods: ['GET'])]
    public function deleteBookById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $libraryRepository->remove($book, true);

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/update/book/{id}/{value}', name: 'update_book', methods: ['GET'])]
    public function updateBook(
        LibraryRepository $libraryRepository,
        int $id,
        string $value
    ): Response {
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $book->setImgLink($value);
        $libraryRepository->save($book, true);

        return $this->redirectToRoute('library_show_all');
    }
}
