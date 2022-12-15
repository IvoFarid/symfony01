<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\VotesPost;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\VotesPostRepository;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\RelationRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/posts')]
class PostController extends AbstractController
{
  public function __construct(VotesPostRepository $votesPostRepository, PostRepository $postRepository, UserRepository $UserRepository, EntityManagerInterface $em)
  {
    $this->UserRepository = $UserRepository;
    $this->VotesPostRepository = $votesPostRepository;
    $this->PostRepository = $postRepository;
    $this->em = $em;
  }
    #[Route('', name: 'app_post_index', methods: ['GET', 'POST'])]
    public function orderedByLatest(Request $request, PostRepository $pr, VotesPostRepository $votesPost, RelationRepository $relations, ValidatorInterface $validator, Security $security): Response
    {
      $posts = $this->PostRepository->getLatest();
      $post = new Post();
      // dd($posts);
      // $vote = new VotesPost();

      $form = $this->createForm(PostType::class, $post);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $errors = $validator->validate($data);
            
            if (count($errors) == 0) {
              
              $data->setAuthor($security->getUser());
              $data->setUpVotes(0);
              $data->setDownVotes(0);
              $this->em->persist($data);
              $this->em->flush();
              return $this->renderForm('post/index.html.twig', [
                'posts' => $posts,
                'form' => $form,
                'votesPost' => $votesPost,
                'relations' => $relations
            ]);
            }
          }
      return $this->renderForm('post/index.html.twig', [
        'posts' => $posts,
        'form' => $form,
        'votesPost' => $votesPost,
        'relations' => $relations
    ]);
    }

    #[Route('/oldest', name: 'app_post_order_2', methods: ['GET', 'POST'])]
    public function orderedByOldest(Request $request, PostRepository $pr, VotesPostRepository $votesPost, RelationRepository $relations, ValidatorInterface $validator, Security $security): Response
    {
      $posts = $this->PostRepository->getOldest();
      $post = new Post();
      // dd($posts);
      // $vote = new VotesPost();

      $form = $this->createForm(PostType::class, $post);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $errors = $validator->validate($data);
            
            if (count($errors) == 0) {
              
              $data->setAuthor($security->getUser());
              $data->setUpVotes(0);
              $data->setDownVotes(0);
              $this->em->persist($data);
              $this->em->flush();
              return $this->renderForm('post/index.html.twig', [
                'posts' => $posts,
                'form' => $form,
                'votesPost' => $votesPost,
                'relations' => $relations
            ]);
            }
          }

      return $this->renderForm('post/index.html.twig', [
        'posts' => $posts,
        'form' => $form,
        'votesPost' => $votesPost,
        'relations' => $relations
    ]);
    }

    #[Route('/top', name: 'app_post_order_3', methods: ['GET', 'POST'])]
    public function orderedByTop(Request $request, PostRepository $pr, VotesPostRepository $votesPost, RelationRepository $relations, ValidatorInterface $validator, Security $security): Response
    {
      $posts = $this->PostRepository->getTop();
      $post = new Post();
      // dd($posts);
      // $vote = new VotesPost();

      $form = $this->createForm(PostType::class, $post);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $errors = $validator->validate($data);
            
            if (count($errors) == 0) {
              
              $data->setAuthor($security->getUser());
              $data->setUpVotes(0);
              $data->setDownVotes(0);
              $this->em->persist($data);
              $this->em->flush();
              return $this->renderForm('post/index.html.twig', [
                'posts' => $posts,
                'form' => $form,
                'votesPost' => $votesPost,
                'relations' => $relations
            ]);
            }
          }

      return $this->renderForm('post/index.html.twig', [
        'posts' => $posts,
        'form' => $form,
        'votesPost' => $votesPost,
        'relations' => $relations
    ]);
    }

    #[Route('/following', name: 'app_post_following', methods: ['GET','POST'])]
    public function following(Request $request, PostRepository $postRepository, RelationRepository $relations, Security $sec): Response
    {
      return $this->render('post/followed.html.twig');
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
    public function show(ValidatorInterface $validator, Security $security, Post $post, Request $request, CommentRepository $commentRepository, PostRepository $postRepository, $id): Response
    {   
        $post = $this->PostRepository->findOneById($id);
        // $userVote = $this->VotesPostRepository->findByPostIdUserId($security->getUser(), $id);
        $userVote = $this->VotesPostRepository->findOneBy(['userId' => $security->getUser(), 'postId' => $post]);
        // dd($userVote);
        if($userVote != null) {
          $voted = $userVote->isVoted();
          if ($voted) {
            $voteDirection = $userVote->isDirection();
          } else {
            $voteDirection = null;
          }
        } else if($userVote == null) {
          $voted = null;
          $voteDirection = null;
        }
        // $voteDirection = $userVote->isDirection();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $data = $form->getData();
          $errors = $validator->validate($data);
          if (count($errors) == 0) {
            $data->setAuthor($security->getUser());           
            $data->setPostId($postRepository->findOneById($id));
            $commentRepository->add($comment, true);
            return $this->redirectToRoute('app_post_show', ['id' => $id]);
          }
        }
        // dd(count($post->getComments()));
        $comments = $commentRepository->getCommentsOnPost($id);
        return $this->render('post/show.html.twig', [
            'comments' => $comments,
            'post' => $postRepository->find($post->getId()),
            'form' => $form->createView(),
            'voted' => $voted,
            'voteDirection' => $voteDirection            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->add($post, true);
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
  
    #[Route('/delete/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete($id, Request $request, Post $post, PostRepository $postRepository, VotesPostRepository $votesPostRepository, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            if($votesPostRepository->findByPostId($id)){
              // dd($votesPostRepository->findByPostId($id));
              $votes = $votesPostRepository->findByPostId($id);
              foreach($votes as $vote) {
                $votesPostRepository->remove($vote);
              }
            }
            if($commentRepository->findByPostId($id)!=null){
              $comments = $commentRepository->findByPostId($id);
              foreach($comments as $comment){
                $commentRepository->remove($comment);
              }
              // foreach($commentRepository->findByPostId($id) as $comment){
              //   $comment->remove();
              // }
            }
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}