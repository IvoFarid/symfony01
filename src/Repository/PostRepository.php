<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPosts() {
      $qb = $this->createQueryBuilder('p')
                  ->select('p.id', 'p.title','p.description', 'p.upVotes','p.downVotes','u.email');
      $qb->leftJoin('App\Entity\User', 'u', \Doctrine\ORM\Query\Expr\Join::WITH,
      'p.author = u.id');
      $qb->orderBy('p.id','ASC');
      return $qb->getQuery()->getResult();
    }

    public function getPostsByAuthor($id) {
      $qb = $this->createQueryBuilder('p')
            ->where('p.author = :id')
            ->select('p.title', 'p.upVotes', 'p.downVotes', 'p.description', 'p.id', 'p.createdAt');
      $qb->setParameter('id', $id);
      $qb->orderBy('p.id','DESC');
      return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Post[] Returns an array of Post objects
//     */
   public function getLatest(): array
   {
       return $this->createQueryBuilder('p')
           ->orderBy('p.createdAt', 'DESC')
          //  ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function getOldest(): array
   {
    return $this->createQueryBuilder('p')
           ->orderBy('p.createdAt', 'ASC')
          //  ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function getTop(): array
   {
    return $this->createQueryBuilder('p')
           ->orderBy('p.upVotes', 'DESC')

          //  ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
