<?php

namespace App\Controller\Rest;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;

class OrderController extends AbstractController {

	/**
	 * @throws ORMException
	 */
	#[Route('/api/order/new', name: 'api_order_new', methods: ['POST', 'GET'], format: 'json')]
	public function new(Request $request, EntityManagerInterface $em) :Response {

		$requestData = json_decode( $request->getContent());
		$csrfToken = $requestData->csrfToken;

		if ($this->isCsrfTokenValid('product_list', $csrfToken)) {
			$productIds = $requestData->productIds;
			$userId = $requestData->userId;

			$products = [];
			foreach($productIds as $productId) {
				$products[$productId] = $em->getReference('App\Entity\Product', $productId);
			}
			//$user = $em->getReference('App\Entity\User', $userId);

			$order = new Order();
			$order->setCreateAt(new \DateTimeImmutable());
			$order->setUpdateAt(new \DateTimeImmutable());
			$order->setUser($this->getUser());
			$order->setProducts($products);
			$order->setStatus(Order::STATUS_CONFIRMED);
			$em->persist($order);
			$em->flush();

			return $this->json(['orderId' => $order->getId()]);
		}

		$response = new Response();
		$response->setStatusCode(Response::HTTP_FORBIDDEN);
		return $response;
	}

}