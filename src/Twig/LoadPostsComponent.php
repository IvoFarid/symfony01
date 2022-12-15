<?php
namespace App\Twig;

use App\Repository\PostRepository;
use App\Entity\User;
use App\Repository\RelationRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('load_posts')]
class LoadPostsComponent extends AbstractController
{
  use DefaultActionTrait;

  // #[LiveProp]
  // public $id;

  #[LiveProp]
  public bool $clicked = false;

  #[LiveProp]
  public $loadedPosts= null;

  public function __construct(RelationRepository $rr, Security $sec){
    $this->relations = $rr->findFollowed($sec->getUser());
    $this->clicked=false;
  }

  #[LiveAction]
  public function loadPosts(#[LiveArg] string $id, #[LiveArg] bool $value, PostRepository $posts)
  {
    if ($value) {
      $this->loadedPosts = $posts->findByAuthor($id);
      // dd($loadedPosts);
      $this->clicked = true;
    } else {
      $this->loadedPosts = $posts->findByAuthor($id);
      $this->clicked = false;
    }
    
  }
}
?>