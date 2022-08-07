<?php

namespace App\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use DateTime;

class GetNewUserAction extends AbstractController
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

        // Find new users (user inserted in the base during current month)
        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(u)')
            ->from(User::class, 'u')
            ->where('u.registeredAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
        $query = $qb->getQuery();
        $newUsers = $query->getSingleScalarResult();

        return new JsonResponse(['Nombre de nouveaux clients' => $newUsers]);
    }
}