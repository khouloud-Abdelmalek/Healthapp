<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\SujetRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum')]
class ForumController extends AbstractController
{
    // Route pour afficher tous les sujets du forum
    #[Route('/', name: 'forum_index')]
    public function index(SujetRepository $sujetRepository): Response
    {
        return $this->render('forum/index.html.twig', [
            'sujets' => $sujetRepository->findAll(),
        ]);
    }

    // Route pour afficher un sujet spécifique avec ses commentaires
    #[Route('/{id}', name: 'forum_show', methods: ['GET'])]
    public function show(Sujet $sujet): Response
    {
        return $this->render('forum/show.html.twig', [
            'sujet' => $sujet,
        ]);
    }

    // Route pour ajouter un commentaire à un sujet
    #[Route('/{id}/comment', name: 'add_comment', methods: ['POST'])]
    public function addComment(
        Sujet $sujet, 
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        $content = $request->request->get('content');

        if (!empty(trim($content))) {
            $comment = new Comment();
            $comment->setContent($content);
            $comment->setCreatedAt(new \DateTime());
            $comment->setSujet($sujet); 

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');
        } else {
            $this->addFlash('danger', 'Le commentaire ne peut pas être vide.');
        }

        return $this->redirectToRoute('forum_show', ['id' => $sujet->getId()]);
    }
}
