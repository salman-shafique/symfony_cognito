<?php

namespace App\Controller;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Exception\AwsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
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
     * @Route("/login", name="app_login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $result = $this->cognitoClient->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                'ClientId' => $this->cognitoClientId,
                'UserPoolId' => $this->cognitoUserPoolId,
                'AuthParameters' => [
                    'USERNAME' => $request->request->get('username'),
                    'PASSWORD' => $request->request->get('password'),
                    'SECRET_HASH' => base64_encode(hash_hmac('sha256', $request->request->get('username') . $this->cognitoClientId, $this->cognitoClientSecret, true))
                ],
            ]);
            if (isset($result['AuthenticationResult']['AccessToken'])) {
                return new JsonResponse(['data' => $result['AuthenticationResult']]);
            } else {
                return new JsonResponse(['error' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (AwsException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $userAttributes = [
                [
                    'Name' => 'email',
                    'Value' => $request->request->get('email')
                ],
                [
                    'Name' => 'name',
                    'Value' => $request->request->get('name')
                ],
            ];
            $result = $this->cognitoClient->signUp([
                'ClientId' => $this->cognitoClientId,
                'SecretHash' => base64_encode(hash_hmac('sha256', $request->request->get('username') . $this->cognitoClientId, $this->cognitoClientSecret, true)),
                'Username' => $request->request->get('username'),
                'Password' => $request->request->get('password'),
                'UserAttributes' => $userAttributes,
            ]);
            return new JsonResponse(['message' => 'User registered successfully', 'data' => $result->toArray()], Response::HTTP_CREATED);
        } catch (AwsException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/confirm", name="confirm_user", methods={"POST"})
     */
    public function confirmUser(Request $request): JsonResponse
    {
        $username = $request->request->get('username');
        $confirmationCode = $request->request->get('code');

        try {
            $result = $this->cognitoClient->confirmSignUp([
                'ClientId' => $this->cognitoClientId,
                'SecretHash' => base64_encode(hash_hmac('sha256', $username . $this->cognitoClientId, $this->cognitoClientSecret, true)),
                'Username' => $username,
                'ConfirmationCode' => $confirmationCode,
            ]);
            return new JsonResponse(['message' => 'User confirmed successfully', 'data' => $result->toArray()]);
        } catch (AwsException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }




}
