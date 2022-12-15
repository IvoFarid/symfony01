<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\VotesPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('comment')]
class CommentController extends AbstractController
{
    public function __construct(PostRepository $postRepository, VotesPostRepository $votesPostRepository){
      $this->PostRepository = $postRepository;
      $this->VotesPostRepository = $votesPostRepository;
    }
    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete($id, Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $postId = $comment->getPostId();
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            // $commentRepository->remove($comment, true);
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_post_show', ['id'=>$postId], Response::HTTP_SEE_OTHER);
    } 

    // #[Route('/{uuid}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    // {
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $commentRepository->add($comment, true);

    //         return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('comment/edit.html.twig', [
    //         'comment' => $comment,
    //         'form' => $form,
    //     ]);
    // }
  }