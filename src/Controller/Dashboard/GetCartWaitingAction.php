<?php

namespace App\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Cart;
use DateTime;

class GetCartWaitingAction extends AbstractController
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
            WHERE `created_at` BETWEEN '2020-01-01' AND '2022-08-07' AND status_id = 3
        */

        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(c)')
            ->from(Cart::class, 'c')
            ->where('c.createdAt BETWEEN :startDate AND :endDate')
            ->andWhere('c.status = 3')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $query = $qb->getQuery();
        $cartWaiting = $query->getOneOrNullResult();

        return new JsonResponse(['Nombre de panier en cours' => $cartWaiting]);
    }
}