<?php

namespace App\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\ProductInCart;
use DateTime;

class GetAverageCartPriceAction extends AbstractController
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
            SELECT SUM(`price`*`quantity`) / COUNT(`cart_id`)
            FROM `product_in_cart`
            JOIN cart
            ON product_in_cart.cart_id = cart.id
            WHERE cart.created_at BETWEEN '2020-01-01' AND '2022-08-07'
        */

        $qb = $this->entityManager->createQueryBuilder()
            ->select('SUM(p.price * p.quantity) / COUNT(p.cart)')
            ->from(ProductInCart::class, 'p')
            ->join('p.cart', 'c')
            ->where('c.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $query = $qb->getQuery();
        $averageCartPrice = $query->getOneOrNullResult();

        return new JsonResponse(['Montant moyen des paniers' => $averageCartPrice]);
    }
}