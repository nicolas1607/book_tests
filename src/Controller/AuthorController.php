<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/author", name="author")
     */
    public function index(): Response
    {
        $authors = $this->em->getRepository(Author::class)->findAll();
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/author/add", name="add_author")
     */
    public function add(Request $request): Response
    {
        $cat = new Author();
        $addCatForm = $this->createForm(AuthorFormType::class, $cat);

        $addCatForm->handleRequest($request);

        if ($addCatForm->isSubmitted() && $addCatForm->isValid()) {
            $author = $addCatForm->getData();

            $this->em->persist($author);
            $this->em->flush(); // faire l'action en BD

            return $this->redirectToRoute('author');
        }

        return $this->render('author/add.html.twig', [
            'add_author_form' => $addCatForm->createView()
        ]);
    }

    /**
     * @Route("/author/delete/{id}", name="delete_author")
     */
    public function delete(Author $id): Response
    {
        $this->em->remove($id);
        $this->em->flush(); // faire l'action en BD

        return $this->redirectToRoute('author');
    }

    /**
     * @Route("/author/modify/{id}", name="modify_author")
     */
    public function modify(Request $request, Author $id): Response
    {
        $updateCatForm = $this->createForm(AuthorFormType::class, $id);

        $updateCatForm->handleRequest($request);

        if ($updateCatForm->isSubmitted() && $updateCatForm->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('author');
        }

        return $this->render('author/modify.html.twig', [
            'modify_author_form' => $updateCatForm->createView()
        ]);
    }
}
