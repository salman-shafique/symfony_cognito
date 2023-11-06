<?php

namespace App\Controller;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Exception\AwsException;
use Aws\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CitiesController extends AbstractController
{
    private $cognitoClient;

    public function __construct(
        CognitoIdentityProviderClient $cognitoClient,
        string $cognitoClientId,
        string $cognitoClientSecret,
        string $cognitoUserPoolId
    )
    {
        $this->cognitoClient = $cognitoClient;
        $this->cognitoClientId = $cognitoClientId;
        $this->cognitoClientSecret = $cognitoClientSecret;
        $this->cognitoUserPoolId = $cognitoUserPoolId;
    }


    /**
     * @Route("/cities", name="cities_list", methods={"GET"})
     */
    public function listCities(Request $request): JsonResponse
    {
        try {
            $authorizationHeader = $request->headers->get('Authorization');
            if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
                $token = $matches[1];
                if ($token) {
                    $result = $this->cognitoClient->getUser([
                        'AccessToken' => $token
                    ]);
                    if ($result instanceof Result) {
                        if ($result->hasKey('@metadata')) {
                            $metadata = $result->get('@metadata');
                            if (isset($metadata['statusCode']) && $metadata['statusCode'] === 200) {
                                $cities = [
                                    'New York',
                                    'Los Angeles',
                                    'Chicago',
                                    'Houston',
                                    'Phoenix',
                                    'Philadelphia',
                                    'San Antonio',
                                    'San Diego',
                                    'Dallas',
                                    'San Jose'
                                ];
                                return new JsonResponse(['data' => $cities]);
                            } else {
                                return new JsonResponse(['error' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
                            }
                        }
                    }
                } else {
                    return new JsonResponse(['error' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
                }
            } else {
                return new JsonResponse(['error' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (AwsException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
