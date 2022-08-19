<?php

namespace App\Controller\Back;

use App\Entity\PaymentMethod;
use App\Form\PaymentMethodType;
use App\Repository\PaymentMethodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/payment/method')]
class PaymentMethodController extends AbstractController
{
    #[Route('/', name: 'app_admin_payment_method_index', methods: ['GET'])]
    public function index(PaymentMethodRepository $paymentMethodRepository): Response
    {
        return $this->render('back/payment_method/index.html.twig', [
            'payment_methods' => $paymentMethodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_payment_method_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentMethodRepository $paymentMethodRepository): Response
    {
        $paymentMethod = new PaymentMethod();
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethodRepository->add($paymentMethod, true);

            return $this->redirectToRoute('app_admin_payment_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/payment_method/new.html.twig', [
            'payment_method' => $paymentMethod,
            'form' => $form,
        ]);
    }

    #[Route('//edit{id}', name: 'app_admin_payment_method_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentMethod $paymentMethod, PaymentMethodRepository $paymentMethodRepository): Response
    {
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethodRepository->add($paymentMethod, true);

            return $this->redirectToRoute('app_admin_payment_method_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/payment_method/edit.html.twig', [
            'payment_method' => $paymentMethod,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_payment_method_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentMethod $paymentMethod, PaymentMethodRepository $paymentMethodRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentMethod->getId(), $request->request->get('_token'))) {
            $paymentMethodRepository->remove($paymentMethod, true);
        }

        return $this->redirectToRoute('app_admin_payment_method_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_admin_payment_method_show', methods: ['GET'])]
    public function show(PaymentMethod $paymentMethod): Response
    {
        return $this->render('back/payment_method/show.html.twig', [
            'payment_method' => $paymentMethod,
        ]);
    }
}
