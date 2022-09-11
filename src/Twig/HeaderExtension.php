<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HeaderExtension extends AbstractExtension
{

    public function __construct(
        private CategoryRepository $categoryRepository
    ) { }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('functionTwigGetCategories', [$this, 'getCategories']),
        ];
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
}
