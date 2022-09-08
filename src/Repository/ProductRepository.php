<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends AbstractBusinessCaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQbAll(): QueryBuilder
    {
        $qb = parent::getQbAll();
        return $qb->select('product', 'brand', 'category')
            ->leftJoin('product.brand', 'brand')
            ->leftJoin('product.category', 'category')
            ->orderBy('product.isOnline', 'DESC')
        ;
    }

    public function getBestSeller()
    {
        $qb = $this->createQueryBuilder('product')
            ->join('product.productInCarts', 'productInCarts')
            ->groupBy('product')
            ->orderBy('COUNT(productInCarts.product)', 'DESC')
            ->setMaxResults(6);

            return $qb->getQuery()
                ->getResult()
            ;
    }
}
