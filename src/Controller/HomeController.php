<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
    ): Response
    {
        return $this->render('front/home/index.html.twig', [
            'bestSellers' => $productRepository->getBestSeller(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
