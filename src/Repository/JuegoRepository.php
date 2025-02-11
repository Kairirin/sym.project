<?php

namespace App\Repository;

use App\Entity\Juego;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Juego>
 */
class JuegoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Juego::class);
    }

    public function remove(Juego $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findJuegos(string $ordenacion, string $tipoOrdenacion)
    {
        $qb = $this->createQueryBuilder('juego');
        $qb->addSelect('plataforma')
            ->innerJoin('juego.plataforma', 'plataforma')
            ->orderBy('juego.' . $ordenacion, $tipoOrdenacion);
        return $qb->getQuery()->getResult();
    }

    public function findJuegosPorPlataforma(array $plats): array
    {
        $qb = $this->createQueryBuilder('j')
            ->andWhere('j.plataforma IN (:plat)')
            ->setParameter('plat', $plats)
            ->orderBy('j.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    //    /**
    //     * @return Juego[] Returns an array of Juego objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Juego
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
