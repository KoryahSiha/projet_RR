<?php

namespace App\Repository;

use App\Entity\GestionnaireSalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GestionnaireSalle>
 *
 * @method GestionnaireSalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestionnaireSalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestionnaireSalle[]    findAll()
 * @method GestionnaireSalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestionnaireSalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GestionnaireSalle::class);
    }

    public function save(GestionnaireSalle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GestionnaireSalle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return GestionnaireSalle[] Retourne un tableau avec les objets de GestionnaireSalle, triés par ordre croissant des noms
     */
    public function findAllGestionnaireSalles(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return GestionnaireSalle[] Returns an array of GestionnaireSalle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GestionnaireSalle
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
