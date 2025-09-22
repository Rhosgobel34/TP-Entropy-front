<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends AbstractController
{
    public function __construct(
    ) {
    }

    public function index(Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'token' => $request->cookies->get('token') ?? '',
        ]);
    }
}
