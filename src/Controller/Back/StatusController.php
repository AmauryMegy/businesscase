<?php

namespace App\Controller\Back;

use App\Entity\Status;
use App\Form\Filter\StatusFilterType;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/status')]
class StatusController extends AbstractController
{
    public function __construct(
        private TextService $textService,
        private EntityManagerInterface $entityManager
    ) { }

    #[Route('/', name: 'app_admin_status_index', methods: ['GET'])]
    public function index(
        StatusRepository $statusRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $builderUpdater,
        Request $request
    ): Response
    {
        $qb = $statusRepository->getQbAll();

        $filterForm = $this->createForm(StatusFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }

        $status = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('back/status/index.html.twig', [
            'status' => $status,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(StatusType::class, new Status());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Status $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/status/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{slug}', name: 'app_admin_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Status $status): Response
    {
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Status $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_status_index');
        }

        return $this->render('back/status/edit.html.twig', [
            'form' => $form->createView(),
            'status' => $status,
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_admin_status_delete', methods: ['POST'])]
    public function delete(Request $request, Status $status, StatusRepository $statusRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$status->getSlug(), $request->request->get('_token'))) {
            $statusRepository->remove($status, true);
        }

        return $this->redirectToRoute('app_admin_status_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}', name: 'app_admin_status_show', methods: ['GET'])]
    public function show(Status $status): Response
    {
        return $this->render('back/status/show.html.twig', [
            'status' => $status,
        ]);
    }
}
