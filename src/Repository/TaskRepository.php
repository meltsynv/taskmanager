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
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumberOfTasks($user_id)
    {
        var_dump($user_id);
        return $qb = $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAllFinishedTasks()
    {
        return $qb = $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.isdone = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAllFinishedTasksFancy($params = [])
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->select('count(t.id)')
            ->where(
                $qb->expr()->eq('t.isdone', ':done')
            );
        $qryCfg = [
            'done' => true,
        ];

        if (\count($params)) {
            if (isset($params['date'])) {
                $qb->andWhere(
                    $qb->expr()->lt('t.date', ':date')
                );

                $qryCfg['date'] = $params['date'];
            }
        }

        $data = $qb
            ->setParameters($qryCfg)
            ->getQuery()
            ->getSingleScalarResult();

        return $data;
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