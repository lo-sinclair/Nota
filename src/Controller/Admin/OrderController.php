<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/order')]
class OrderController extends AbstractController {

	#[Route('/', name: 'app_order_index', methods: ['GET'])]
	public function index(OrderRepository $orderRepository): Response
	{
		return $this->render('admin/order/index.html.twig', [
			'orders' => $orderRepository->findAll(),
		]);
	}

	#[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, Order $order, EntityManagerInterface $entityManager,
		OrderRepository $orderRepository, FileUploader $fileUploader): Response
	{
		$form = $this->createForm(OrderType::class, $order);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$order->setUpdateAt(new \DateTimeImmutable());

			$entityManager->flush();

			return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
		}


		return $this->render('admin/order/edit.html.twig', [
			'order' => $order,
			'order_sum' => $orderRepository->sumOfOrder( $order->getId() ),
			'form' => $form,
		]);
	}

	#[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
	public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
	{
		if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->get('_token'))) {
			$entityManager->remove($order);
			$entityManager->flush();
		}

		return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
	}


}