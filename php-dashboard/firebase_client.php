<?php

declare(strict_types=1);

final class FirebaseClient
{
    private string $databaseUrl;
    private ?string $authToken;

    public function __construct(string $databaseUrl, ?string $authToken = null)
    {
        $this->databaseUrl = rtrim($databaseUrl, '/');
        $this->authToken = $authToken;
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $path = '/'): array
    {
        $endpoint = $this->buildEndpoint($path);
        $response = @file_get_contents($endpoint);

        if ($response === false) {
            return [
                'ok' => false,
                'data' => [],
                'error' => 'Unable to connect to Firebase. Check FIREBASE_DATABASE_URL and internet access.',
            ];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'ok' => false,
                'data' => [],
                'error' => 'Firebase returned an invalid JSON response.',
            ];
        }

        return [
            'ok' => true,
            'data' => is_array($decoded) ? $decoded : [],
            'error' => null,
        ];
    }

    private function buildEndpoint(string $path): string
    {
        $path = trim($path, '/');
        $resource = $path === '' ? '.json' : sprintf('/%s.json', $path);

        if ($this->authToken === null || $this->authToken === '') {
            return $this->databaseUrl . $resource;
        }

        return $this->databaseUrl . $resource . '?auth=' . urlencode($this->authToken);
    }
}
