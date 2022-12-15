<?php

namespace App\Controller;
use App\Service;
use App\Repository\VotesPostRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Filesystem\Filesystem;
use App\Form\EditUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;

#[Route('/{nick}')]
class UserController extends AbstractController
{

  #[Route('', methods:["GET","POST"], name:"user_profile")]
  public function index($nick, Request $request, Security $sec, UserRepository $userRepo, PostRepository $postRepo, VotesPostRepository $votesPost): Response
  {
    if ($sec->getUser()){
      $mainUser = $sec->getUser()->getNick();
      if($mainUser == $nick){
        $foreignUser=null;
        $isYourProfile = true;
        $mainUser = $sec->getUser();
        $postsByUser = $postRepo->findByAuthor($mainUser);
      } else {
        $isYourProfile = false;
        $foreignUser = $userRepo->getForeignUser($nick);
        $postsByUser = $postRepo->findByAuthor($foreignUser[0]['id']);
      }
    } else {
      $mainUser = null;
      $isYourProfile = false;
      $foreignUser = $userRepo->getForeignUser($nick);
      $postsByUser = $postRepo->findByAuthor($foreignUser[0]['id']);
    }
    // dd($sec->getUser()); 
    // dd($this->GetParameter('kernel.project_dir') . '/public/img/userdefault.jpg');
    if($request->isMethod('POST')){
      $imageFile = $request->files->get('image');
      if ($imageFile){
        $newFileName = uniqid() . '.' . $imageFile->guessExtension();
        if($mainUser->getImagePath() !== null){
          $oldFilename = $mainUser->getImagePath();
          if
          (file_exists($this->getParameter('kernel.project_dir') . $mainUser->getImagePath()))
            {
              $this->GetParameter('kernel.project_dir') . $mainUser->getImagePath();
            }
      }
      try {
        $imageFile->move(
            $this->getParameter('kernel.project_dir') . '/public/uploads/',
            $newFileName
        );
        // $root = str_replace("\\",'/',$this->GetParameter('kernel.project_dir'));
        $oldFilename = str_replace("/",'\\',$oldFilename);
        // dd($this->GetParameter('kernel.project_dir') . $oldFilename);
        // $filesystem = new Filesystem();
        // $filesystem->remove($root . $oldFilename);


        if
          (file_exists($this->getParameter('kernel.project_dir')  . '\public\\' . $oldFilename))
            {
              unlink($this->GetParameter('kernel.project_dir') . '\public\\' . $oldFilename);
            }
        
        } catch (FileException $e){
            return new Response($e->getMessage());
        }
        
        $mainUser->setImagePath('/uploads/' . $newFileName);
        $userRepo->add($mainUser, true);
        return $this->redirect($request->getUri());
        } else {
          // prevent refresh is there's any image selected
        }
      }
    return $this->render('user/index.html.twig', [
      'isYourProfile' => $isYourProfile,
      'mainUser' => $mainUser,
      'nick' => $nick,
      'foreignUser' => $foreignUser,
      'postsByUser' => $postsByUser,
      'votesPost' => $votesPost
  ]);
}


  #[Route('/settings', methods:["GET","POST"], name:"user_profile_edit")]
  public function edit(Security $sec, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository):Response
  {
    $user = $sec->getUser();
    $form = $this->createForm(EditUserFormType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $nick = $form->getData()->getNick();
      $email = $form->getData()->getEmail();
      $password = $form->get('plainPassword')->getData();
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $password
          )
        );
      $user->setEmail($email);
      $user->setNick($nick);
      $userRepository->add($user, true);
      return $this->redirectToRoute('user_profile', [], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('user/edit.html.twig', [
      'user' => $user,
      'form' => $form,
    ]);
  }
}