<?php

namespace App\Repository;

use App\Entity\ExerciseRecordSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExerciseRecordSet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciseRecordSet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciseRecordSet[]    findAll()
 * @method ExerciseRecordSet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseRecordSetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExerciseRecordSet::class);
    }

    // /**
    //  * @return ExerciseRecordSet[] Returns an array of ExerciseRecordSet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExerciseRecordSet
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
