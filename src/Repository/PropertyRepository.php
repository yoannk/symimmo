<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @return Property[]
     */
    public function findAllVisible()
    {
        return $this->findVisibleQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Property[]
     */
    public function findLatest(int $amount)
    {
        return $this->findVisibleQueryBuilder()
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult();
    }

    public function findLatestPaginated(PropertySearch $search, int $page = 1)
    {
        $query = $this->findVisibleQueryBuilder();

        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        if ($search->getMinSurface()) {
            $query = $query
                ->andWhere('p.surface >= :minSurface')
                ->setParameter('minSurface', $search->getMinSurface());
        }

        return $this->createPaginator(
            $query->getQuery(),
            $page
        );
    }

    private function findVisibleQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false');
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Property::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
