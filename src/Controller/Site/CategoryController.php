<?php

namespace App\Controller\Site;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/{id}', name: 'app_category', methods: ['GET'])]
    public function show(Category $category, ProductRepository $productRepository): Response
    {
        return $this->render('site/category/category.html.twig', [
            'category' => $category,
	        'products' => $productRepository->findByCategory($category),
        ]);
    }

}
