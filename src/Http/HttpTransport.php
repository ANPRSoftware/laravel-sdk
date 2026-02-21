<?php

namespace Anpr\LaravelSdk\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Anpr\LaravelSdk\Contracts\TransportInterface;
use Anpr\LaravelSdk\Exceptions\AuthenticationException;
use Anpr\LaravelSdk\Exceptions\InsufficientBalanceException;
use Anpr\LaravelSdk\Exceptions\InvalidImageException;
use Anpr\LaravelSdk\Exceptions\RateLimitException;
use Anpr\LaravelSdk\Exceptions\ServerException;

class HttpTransport implements TransportInterface
{
    private readonly ?\Closure $retryWhen;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey,
        private readonly int    $timeout,
        private readonly int    $retryTimes,
        private readonly int    $retrySleepMs,
        ?\Closure $retryWhen = null,
    ) {
        $this->retryWhen = $retryWhen;
    }

    public function postMultipart(string $uri, mixed $image, array $params = []): array
    {
        $request = $this->authenticatedRequest()
            ->attach('file', $this->resolveImageStream($image), 'image.jpg');

        $formData = [];

        if (! empty($params)) {
            $formData['params'] = json_encode($params);
        }

        $response = $request->post($this->url($uri), $formData);

        return $this->handleResponse($response);
    }

    public function get(string $uri, array $query = []): array
    {
        $response = $this->authenticatedRequest()->get($this->url($uri), $query);

        return $this->handleResponse($response);
    }

    public function getPublic(string $uri): array
    {
        $response = Http::timeout($this->timeout)->get($this->url($uri));

        return $this->handleResponse($response);
    }

    private function authenticatedRequest(): PendingRequest
    {
        return Http::withToken($this->apiKey)
            ->timeout($this->timeout)
            ->retry(
                $this->retryTimes,
                $this->retrySleepMs,
                $this->retryWhen ?? fn (\Exception $e) => $e->getCode() >= 500,
            );
    }

    private function handleResponse(Response $response): array
    {
        if ($response->successful()) {
            return $response->json();
        }

        $body   = $response->json();
        $detail = $body['detail'] ?? $response->body();

        match ($response->status()) {
            400 => throw new InvalidImageException($detail, 400),
            401 => throw new AuthenticationException($detail, 401),
            402 => throw new InsufficientBalanceException(
                $detail,
                required:  (float) ($body['required'] ?? 0),
                available: (float) ($body['available'] ?? 0),
            ),
            429 => throw new RateLimitException(
                $detail,
                retryAfterSeconds: $response->header('Retry-After')
                    ? (int) $response->header('Retry-After')
                    : null,
            ),
            default => throw new ServerException($detail, $response->status()),
        };
    }

    private function url(string $uri): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($uri, '/');
    }

    private function resolveImageStream(mixed $image): mixed
    {
        if (is_string($image) && file_exists($image)) {
            return fopen($image, 'r');
        }

        if ($image instanceof \SplFileInfo) {
            return fopen($image->getRealPath(), 'r');
        }

        // Already a resource/stream
        return $image;
    }
}
