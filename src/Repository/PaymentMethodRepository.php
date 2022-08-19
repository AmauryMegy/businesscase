<?php

namespace App\Repository;

use App\Entity\PaymentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentMethod>
 *
 * @method PaymentMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentMethod[]    findAll()
 * @method PaymentMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentMethodRepository extends AbstractBusinessCaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
    }

    public function add(PaymentMethod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PaymentMethod $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQbAll(): QueryBuilder
    {
        $qb = parent::getQbAll();
        return $qb->select('paymentmethod')
            ->orderBy('paymentmethod.name', 'ASC');
        ;
    }
}
