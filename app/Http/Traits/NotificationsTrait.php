<?php

namespace App\Http\Traits;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

trait NotificationsTrait
{
    private function createJwt(array $serviceAccountKey): string
    {
        $nowSeconds = time();
        $payload = [
            'iss' => $serviceAccountKey['client_email'],
            'sub' => $serviceAccountKey['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $nowSeconds,
            'exp' => $nowSeconds + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ];

        return JWT::encode($payload, $serviceAccountKey['private_key'], 'RS256');
    }

    private function getAccessToken(array $serviceAccountKey): string
    {
        $jwt = $this->createJwt($serviceAccountKey);

        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['access_token'];
    }

    private function sendNotificationToFCM(string $accessToken, string $projectId, array $message)
    {
        $client = new Client();
        $response = $client->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => $message
        ]);

        return $response->getBody()->getContents();
    }

    public function sendNotification(string $title, string $body, array $tokens = [],  string $sound = 'default', string $priority = 'high')
    {
        $serviceAccountPath = storage_path('app/firebase/edusphere-3d0b0-firebase-adminsdk-419cy-22e5aaad3c.json');
        $serviceAccountJson = file_get_contents($serviceAccountPath);
        $serviceAccountKey = json_decode($serviceAccountJson, true); 
        $accessToken = $this->getAccessToken($serviceAccountKey);
        $projectId = 'edusphere-3d0b0';
        $sendTokens = array_filter($tokens, fn($token) => !empty($token));
        $successfulNotification = 0;
        foreach ($sendTokens as $key => $sendToken) {
            $message [$key]= [
                'message' => [
                    'token' => $sendToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => $sound,
                        'priority' => $priority,
                    ],
                ],
            ];
            $this->sendNotificationToFCM($accessToken, $projectId, $message[$key]);
            $successfulNotification++;
        }
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => "notification sent successfully",
            'successfulNotification' => $successfulNotification
        ]);
    }
}