<?php

namespace App\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Cart;
use DateTime;

class GetTotalOrderAction extends AbstractController
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }


    public function __invoke(?\DateTime $startDate = null, ?\DateTime $endDate = null): JsonResponse
    {
        if ($startDate === null || $endDate === null) {
            $startDate = new DateTime('2020-01-01');
            $endDate = new DateTime('now');
        }

        /* SQL request :
            SELECT COUNT(*)
            FROM `cart`
            WHERE `created_at` BETWEEN '2020-01-01' AND '2022-08-07' AND status_id = 1
            OR `created_at` BETWEEN '2020-01-01' AND '2022-08-07' AND status_id = 4
            OR `created_at` BETWEEN '2020-01-01' AND '2022-08-07' AND status_id = 5
            OR `created_at` BETWEEN '2020-01-01' AND '2022-08-07' AND status_id = 6
        */

        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(c)')
            ->from(Cart::class, 'c')
            ->where('c.createdAt BETWEEN :startDate AND :endDate')
            ->andWhere('c.status = 1 OR c.status = 4 OR c.status = 5 OR c.status = 6')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $query = $qb->getQuery();
        $totalOrder = $query->getOneOrNullResult();

        return new JsonResponse(['Nombre total de commande' => $totalOrder]);
    }
}
