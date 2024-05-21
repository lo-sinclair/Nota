<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class MainController extends AbstractController {

	#[Route('/', name: 'app_main_admin')]
	public function index()
	{
		return $this->redirectToRoute('app_product_index');
	}

}