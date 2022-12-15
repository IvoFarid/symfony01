<?php

namespace App\Repository;

use App\Entity\VotesPost;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VotesPost>
 *
 * @method VotesPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method VotesPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method VotesPost[]    findAll()
 * @method VotesPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotesPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VotesPost::class);
    }

    public function add(VotesPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VotesPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByPostIdUserId($uid, $pid) {
      $qb = $this->createQueryBuilder('v')
        ->where('v.postId = :pid','v.userId = :uid')
        ->select('v.voted', 'v.direction');
        $qb->setParameter('uid',$uid);
        $qb->setParameter('pid', $pid);
      return $qb->getQuery()->getResult();
    }

    public function isVotedByUser(User $user, Post $post) : int {
      // $likes = $this->findOneBy(['postId' => strval($id), $user]);
      $like = $this->findByPostIdUserId($user,$post);
      // var_dump($like);
      if($like) {
        foreach($like as $liked){
          if($liked['direction']){
            return 1;
          } else {
            return 0;
          }
        }
      } else { return 2;
        // var_dump($liked['voted']);
        // if($liked->isVoted()){
        //    return true;
        // } else { return false; }
      // }
        }
      // return false;
    }

//    /**
//     * @return VotesPost[] Returns an array of VotesPost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VotesPost
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
