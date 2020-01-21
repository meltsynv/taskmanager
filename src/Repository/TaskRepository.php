<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }


    /**
     * @param $user_id
     * @return array
     */
    public function getTasksByUserId($user_id)
    {
        return $qd = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllFinishedTasks($user_id)
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('t.isdone = 1')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumberOfTasks($user_id)
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $user_id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumOfAllFinishedTasks($user_id)
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('count(t)')
            ->where('t.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->andWhere('t.isdone = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
;