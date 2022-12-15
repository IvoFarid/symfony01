<?php
namespace App\Twig;

use App\Entity\User;
use App\Entity\Relation;
use App\Repository\RelationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\HttpFoundation\Request;

#[AsLiveComponent('follow_sys')]
class FollowSysComponent extends AbstractController
{
    use DefaultActionTrait;

    // #[LiveProp]
    // public ?RelationRepository $relations;

    #[LiveProp]
    public ?bool $clicked=null;

    #[LiveProp]
    public ?bool $value = null;

    #[LiveProp]
    public string $nick = '';
    // #[LiveProp, dehydrateWith]
    // public Relation $relation;

    public function __construct(private RelationRepository $relationRepository,Security $sec, UserRepository $users)
    {
      $this->relation = new Relation();
      $this->user = $sec->getUser();
      $follow = $users->findOneByNick($this->nick);
      $this->relations = $relationRepository;
      if($this->relations->findIfExists($sec->getUser(),$follow)){
        $this->clicked = true;
      } else {
        $this->clicked = false;
      }
    }

    #[LiveAction]
    public function follow(#[LiveArg] bool $value, #[LiveArg] string $nick, UserRepository $users)
    {
      // evaluateRelation();
      if($value == true){
        $follow = $users->findOneByNick($this->nick);
        // dd($follow);
        $this->user->incFollowed();
        $follow->incFollowers();
        $this->relation->setUser($this->user);
        $this->relation->setFollow($follow);
        $this->relationRepository->add($this->relation, true);
        $this->clicked=true;
        return $this->redirect('posts');
      } else {
        $follow = $users->findOneByNick($this->nick);
        $this->user->decFollowed();
        $follow->decFollowers();
        $relation = $this->relationRepository->findIfExists($this->user,$follow);
        $this->relationRepository->remove($relation, true);
        $this->clicked=false;
        return $this->redirect('posts');
      }
    }
}
?>