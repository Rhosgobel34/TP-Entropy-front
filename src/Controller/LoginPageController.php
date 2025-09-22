<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoginPageController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
    ) {
    }

    public function index(): Response
    {
        return $this->render('login/index.html.twig');
    }

    public function form(Request $request): Response
    {
        $data = $request->request->all();

        $response = $this->client->request(
            'POST',
            $this->apiUrl . 'auth/login',
            [
                'json' => [
                    'Username' => $data['username'],
                    'Password' => $data['password'],
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $code = $response->getStatusCode();

        if ($code === 200) {
            $redirect = $this->redirectToRoute('home_page');
            $redirect->headers->setCookie(
                new Cookie(
                    'token',
                    $response->toArray()['token'],
                )
            );

            return $redirect;
        }

        return $this->redirectToRoute('login_page');
    }
}
