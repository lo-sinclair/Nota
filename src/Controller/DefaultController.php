<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
		$product = $productRepository->findOneBy(['id' => 1]);
		$product->setName('Product 100');
		$em->flush();
		dump($product);
		exit;

	   /* $product = (new Product())
		    -> setName('Product2')
		    -> setDescription('Description')
		    -> setPrice(30000);*/
		$em->persist($product);
		$em->flush();
		exit;

        return $this->render('default/index.html.twig', []);
    }
}
