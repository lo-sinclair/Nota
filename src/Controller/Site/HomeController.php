<?php

namespace App\Controller\Site;

use App\Entity\Status;
use App\Entity\Status as StatusAlias;
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
	    return $this->render( 'site/home/home.html.twig', [
			'products_new' => $productRepository->findNewest(),
			'products_promo' => $productRepository->findPromo(),
		    'categories' => $categoryRepository->findTop(),
	    ] );
    }
}
