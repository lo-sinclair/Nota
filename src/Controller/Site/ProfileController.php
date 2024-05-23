<?php

namespace App\Controller\Site;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController {

	#[Route('/profile', name: 'app_profile_show', methods: ['GET'])]
	public function show() :Response {
		if ($this->getUser() == null) {
			return $this->redirectToRoute('app_login');
		}
		return $this->render('site/profile/show.html.twig', [
			'user' => $this->getUser(),
		]);
	}
}