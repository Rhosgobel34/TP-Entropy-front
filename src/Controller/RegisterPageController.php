<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RegisterPageController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
    ) {
    }

    public function index(): Response
    {
        return $this->render('register/index.html.twig');
    }

    public function form(Request $request): Response
    {
        $data = $request->request->all();

        if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\'":\\|,.<>\/?]).{12,}$/', $data['password']) !== 1) {
            return $this->redirectToRoute('register_page', [
                'error' => 'Password must be at least 12 characters long and include at least one uppercase letter, one number, and one special character.'
            ]);
        }

        $response = $this->client->request(
            'POST',
            $this->apiUrl . 'user',
            [
                'json' => [
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'email' => 'ff@ff.com',
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $code = $response->getStatusCode();

        if ($code === 201) {
            return $this->redirectToRoute('login_page');
        }

        return $this->redirectToRoute('register_page');
    }
}
