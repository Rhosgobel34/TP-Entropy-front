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
            'GET',
            $this->apiUrl . 'verify_token',
            [
                'body' => [
                    'token' => $token,
                ],
            ]
        );

        $response = $response->toArray();

        if (!$response['verified']) {
            throw new BadCredentialsException('Invalid credentials.');
        }


        return new UserBadge($token);
    }
}