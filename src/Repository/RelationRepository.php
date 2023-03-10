<?php

namespace App\Repository;

use App\Entity\Relation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Relation>
 *
 * @method Relation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relation[]    findAll()
 * @method Relation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relation::class);
    }

    public function add(Relation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Relation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Relation[] Returns an array of Relation objects
    */
   public function findFollowed($user): array
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.user = :val')
           ->setParameter('val', $user)
          //  ->orderBy('r.id', 'ASC')
          //  ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findIfExists($user, $follow): ?Relation
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.user = :user', 'r.follow = :follow')

           ->setParameter('user', $user)
           ->setParameter('follow', $follow)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
