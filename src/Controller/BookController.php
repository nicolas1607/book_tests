<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/book", name="book")
     */
    public function index(): Response
    {
        $books = $this->em->getRepository(Book::class)->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/book/add", name="add_book")
     */
    public function add(Request $request): Response
    {
        $cat = new Book();
        $addBookForm = $this->createForm(BookFormType::class, $cat);

        $addBookForm->handleRequest($request);

        if ($addBookForm->isSubmitted() && $addBookForm->isValid()) {
            $book = $addBookForm->getData();

            $this->em->persist($book);
            $this->em->flush(); // faire l'action en BD

            return $this->redirectToRoute('book');
        }

        return $this->render('book/add.html.twig', [
            'add_book_form' => $addBookForm->createView()
        ]);
    }

    /**
     * @Route("/book/delete/{id}", name="delete_book")
     */
    public function delete(Book $id): Response
    {
        $this->em->remove($id);
        $this->em->flush(); // faire l'action en BD

        return $this->redirectToRoute('book');
    }

    /**
     * @Route("/book/modify/{id}", name="modify_book")
     */
    public function modify(Request $request, Book $id): Response
    {
        $updateBookForm = $this->createForm(BookFormType::class, $id);

        $updateBookForm->handleRequest($request);

        if ($updateBookForm->isSubmitted() && $updateBookForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('book');
        }

        return $this->render('book/modify.html.twig', [
            'modify_book_form' => $updateBookForm->createView()
        ]);
    }
}
