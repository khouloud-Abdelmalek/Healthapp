<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum_index")
     */
    public function index(): Response
    {
        // Logique pour afficher les forums
        return $this->render('forum/index.html.twig');
    }

    /**
     * @Route("/forum/{id}", name="forum_show")
     */
    public function show(Post $post): Response
    {
        // Logique pour afficher un post spécifique et ses commentaires
        return $this->render('forum/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/forum/{postId}/comment", name="add_comment", methods={"POST"})
     */
    public function addComment(Request $request, $postId): Response
    {
        // Logique pour ajouter un commentaire
        $comment = new Comment();
        $comment->setContent($request->get('content'));
        $comment->setPost($this->getDoctrine()->getRepository(Post::class)->find($postId));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('forum_show', ['id' => $postId]);
    }
}
