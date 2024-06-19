<?php

namespace App\Controller;

use App\Entity\CookingBlog;
use App\Form\CookingBlogType;
use App\Repository\CookingBlogRepository;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookingBlogController extends AbstractController
{
    #[Route('/', name: 'app_cooking_blog_index', methods: ['GET'])]
    public function index(CookingBlogRepository $cookingBlogRepository): Response
    {
        return $this->render('cooking_blog/index.html.twig', [
            'cooking_blogs' => $cookingBlogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cooking_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CookingBlogRepository $cookingBlogRepository): Response
    {
        $cookingBlog = new CookingBlog();
        $form = $this->createForm(CookingBlogType::class, $cookingBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cookingBlog->setUser($this->getUser());
            $cookingBlogRepository->save($cookingBlog, true);

            return $this->redirectToRoute('app_cooking_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cooking_blog/new.html.twig', [
            'cooking_blog' => $cookingBlog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cooking_blog_show', methods: ['GET'])]
    public function show(CookingBlog $cookingBlog, Request $request1, Request $request2, CommentRepository $commentRepository, NoteRepository $noteRepository): Response
    {
        $comment = new Comment();
        $note = new Note();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request1);
        $formNote = $this->createForm(NoteType::class, $note);
        $formNote->handleRequest($request2);
        $all_notes = $cookingBlog->getNotes();
        $sumNotes = 0;
        foreach($all_notes as $note){
            $sumNotes += $note->getNote();
        }

        if(count($all_notes)){
            $meanNotes = round($sumNotes/count($all_notes)*100)/100;
        } else {
            $meanNotes = 0;
        }

        return $this->renderForm('cooking_blog/show.html.twig', [
            'cooking_blog' => $cookingBlog,
            'comments' => $cookingBlog->getComments(),
            'meanNotes' => $meanNotes,
            'numberNotes' => count($all_notes),
            'formComment' => $formComment,
            'formNote' => $formNote,
        ]);
    }

    #[Route('/{id}/comment', name: 'app_cooking_blog_comment', methods: ['POST'])]
    public function newComment(CookingBlog $cookingBlog, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setCookingBlog($cookingBlog);
            $commentRepository->save($comment, true);
        }

        return $this->redirectToRoute('app_cooking_blog_show', ['id' => $cookingBlog->getId()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/note', name: 'app_cooking_blog_note', methods: ['POST'])]
    public function newNote(CookingBlog $cookingBlog, Request $request, NoteRepository $noteRepository): Response
    {
        $note = new Note();
        $formNote = $this->createForm(NoteType::class, $note);
        $formNote->handleRequest($request);

        if ($formNote->isSubmitted() && $formNote->isValid()) {
            $note->setCookingBlog($cookingBlog);
            $noteRepository->save($note, true);
        }

        return $this->redirectToRoute('app_cooking_blog_show', ['id' => $cookingBlog->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_cooking_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CookingBlog $cookingBlog, CookingBlogRepository $cookingBlogRepository): Response
    {
        $form = $this->createForm(CookingBlogType::class, $cookingBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cookingBlogRepository->save($cookingBlog, true);

            return $this->redirectToRoute('app_cooking_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cooking_blog/edit.html.twig', [
            'cooking_blog' => $cookingBlog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cooking_blog_delete', methods: ['POST'])]
    public function delete(Request $request, CookingBlog $cookingBlog, CookingBlogRepository $cookingBlogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cookingBlog->getId(), $request->request->get('_token'))) {
            $cookingBlogRepository->remove($cookingBlog, true);
        }

        return $this->redirectToRoute('app_cooking_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
