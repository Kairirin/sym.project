<?php

namespace App\Repository;

use App\Entity\Juego;
use App\Entity\Review;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[] Returns an array of Review objects
     */
    public function findReviewsPorFecha(int $juego, ?string $fechaInicial = null, ?string $fechaFinal = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.juego = :juego')
            ->setParameter('juego', $juego);

        if (!is_null($fechaInicial) && $fechaInicial !== '') {
            $dtFechaInicial = DateTime::createFromFormat('Y-m-d', $fechaInicial);
            $qb->andWhere($qb->expr()->gte('r.fecha', ':fechaInicial'))
                ->setParameter('fechaInicial', $dtFechaInicial);
        }
        if (!is_null($fechaFinal) && $fechaFinal !== '') {
            $dtFechaFinal = DateTime::createFromFormat('Y-m-d', $fechaFinal);
            $qb->andWhere($qb->expr()->lte('r.fecha', ':fechaFinal'))
                ->setParameter('fechaFinal', $dtFechaFinal);
        }
        return $qb->getQuery()->getResult();
    }

    public function findReviewsPorJuego(int $juego): array
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.juego = :juego')
            ->setParameter('juego', $juego)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function findReviewsPorAutor(int $autor): array
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.autor = :autor')
            ->setParameter('autor', $autor)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    //    /**
    //     * @return Review[] Returns an array of Review objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
