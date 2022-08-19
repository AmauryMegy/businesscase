<?php

namespace App\Controller\Back;

use App\Entity\PaymentMethod;
use App\Form\Filter\PaymentMethodFilterType;
use App\Form\PaymentMethodType;
use App\Repository\PaymentMethodRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/payment/method')]
class PaymentMethodController extends AbstractController
{
    public function __construct(
        private TextService $textService,
        private EntityManagerInterface $entityManager
    ) { }

    #[Route('/', name: 'app_admin_payment_method_index', methods: ['GET'])]
    public function index(
        PaymentMethodRepository $paymentMethodRepository,
        PaginatorInterface $paginator,
        FilterBuilderUpdaterInterface $builderUpdater,
        Request $request
    ): Response
    {
        $qb = $paymentMethodRepository->getQbAll();

        $filterForm = $this->createForm(PaymentMethodFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }

        $payment_methods = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('back/payment_method/index.html.twig', [
            'payment_methods' => $payment_methods,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_payment_method_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $form = $this->createForm(PaymentMethodType::class, new PaymentMethod());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PaymentMethod $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_payment_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/payment_method/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{slug}', name: 'app_admin_payment_method_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentMethod $paymentMethod): Response
    {
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PaymentMethod $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_payment_method_index');
        }

        return $this->render('back/payment_method/edit.html.twig', [
            'form' => $form->createView(),
            'payment_method' => $paymentMethod,
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_admin_payment_method_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentMethod $paymentMethod, PaymentMethodRepository $paymentMethodRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentMethod->getSlug(), $request->request->get('_token'))) {
            $paymentMethodRepository->remove($paymentMethod, true);
        }

        return $this->redirectToRoute('app_admin_payment_method_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}', name: 'app_admin_payment_method_show', methods: ['GET'])]
    public function show(PaymentMethod $paymentMethod): Response
    {
        return $this->render('back/payment_method/show.html.twig', [
            'payment_method' => $paymentMethod,
        ]);
    }
}
