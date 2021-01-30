<?php

namespace App\Repository;

use App\Entity\TempHumidityRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TempHumidityRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempHumidityRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempHumidityRecord[]    findAll()
 * @method TempHumidityRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempHumidityRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempHumidityRecord::class);
    }

    // /**
    //  * @return TempHumidityRecord[] Returns an array of TempHumidityRecord objects
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
    public function findOneBySomeField($value): ?TempHumidityRecord
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
