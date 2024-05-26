<?php

namespace App\Controller\Site;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
		//return $this->redirectToRoute('app_product_index');
	    return $this->render( 'site/home/home.html.twig', [
			'products_new' => $productRepository->findNewest(),
		    'categories' => $categoryRepository->findTop(),
	    ] );
    }
}
