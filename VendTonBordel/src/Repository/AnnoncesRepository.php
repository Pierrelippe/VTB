<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }


    /*public function getCategory()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("SELECT DISTINCT ad.categorie.name, ad.id FROM App\Entity\Annonces ad GROUP BY ad.categorie.name");

        return $query->getResult();
    }

    public function findAdByCategory(string $category)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("SELECT ad.categorie FROM App\Entity\Annonces ad WHERE ad.categorie.name LIKE :categoryname"
        )->setParameter('categoryname', $category);

        return $query->getResult();
    }

    public function findAdByName(string $name)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("SELECT ad FROM App\Entity\Annonces ad WHERE ad.name LIKE :searchname"
        )->setParameter('searchname', '%'.$name.'%');

        return $query->getResult();
    }*/

    // /**
    //  * @return Annonces[] Returns an array of Annonces objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonces
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
