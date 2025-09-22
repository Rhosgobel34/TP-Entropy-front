<?php

namespace App\Utils;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TokenVerifyer implements AccessTokenHandlerInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
    ) {  
    }

    public function getUserBadgeFrom(string $token): UserBadge
    {
        $response = $this->client->request(
            'POST',
            $this->apiUrl . 'auth/verify_token',
            [
                'json' => [
                    'Token' => $token,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $response = $response->toArray();

        if (!$response['verified']) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge('dummy');
    }
}