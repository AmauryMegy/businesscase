<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\Filter\CategoryFilterType;
use App\Repository\CategoryRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    public function __construct(
        private TextService $textService,
        private EntityManagerInterface $entityManager
    ) { }

    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $builderUpdater,
        Request $request
    ): Response
    {
        $qb = $categoryRepository->getQbAll();

        $filterForm = $this->createForm(CategoryFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }

        $categories = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('back/category/index.html.twig', [
            dump($categories),
            'categories' => $categories,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, new Category());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{slug}', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('back/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getSlug(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}', name: 'app_admin_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('back/category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
