<?php

namespace App\Repository;

use App\Entity\MicroController;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MicroController|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroController|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroController[]    findAll()
 * @method MicroController[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroControllerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroController::class);
    }

    public function findAllByUserToArray(User $user){

        $qb = $this->createQueryBuilder('m')
            ->join('m.users', 'u')
            ->where("u.id = :userId")
            ->setParameters(array('userId' => $user->getId()));

        return $qb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return MicroController[] Returns an array of MicroController objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MicroController
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
