<?php
namespace App\Twig;

use App\Entity\VotesPost;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\VotesPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('post_vote')]
class PostVoteComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Post $post;
    
    #[LiveProp]
    public bool $hasVoted = false;

    #[LiveProp]
    public bool $direction = false;

    public function __construct(private PostRepository $postRepository, private VotesPostRepository $votesPostRepository, Security $security)
    {
      $this->votesPost = new VotesPost();
    }
    
    #[LiveAction]
    public function vote(#[LiveArg] string $direction, Security $security)
    {
      $this->votesPost->setPostId($this->post);
      $this->votesPost->setUserId($security->getUser());
      if ('up' === $direction) {   
        $this->votesPost->setDirection(true);
        $this->votesPost->setVoted(true);
        $this->votesPostRepository->add($this->votesPost, true);
        $this->post->upVote();
      } else {
        $this->votesPost->setDirection(false);
        $this->votesPost->setVoted(true);
        $this->votesPostRepository->add($this->votesPost, true);
        $this->post->downVote();
      }
      
      $this->postRepository->add($this->post, true);
      $this->direction = $this->votesPost->isDirection();
      $this->hasVoted = true;
    }
}
?>