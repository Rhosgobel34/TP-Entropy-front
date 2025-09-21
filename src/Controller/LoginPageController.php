<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LoginPageController extends AbstractController
{
    public function index(): Response
    {
        return new Response('<h1>Login works!</h1>');
    }
}
