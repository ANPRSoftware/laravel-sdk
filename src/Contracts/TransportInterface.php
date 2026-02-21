<?php

namespace Anpr\LaravelSdk\Contracts;

interface TransportInterface
{
    /**
     * Send a multipart POST with file upload.
     *
     * @return array<string, mixed>  Decoded JSON response
     */
    public function postMultipart(string $uri, mixed $image, array $params = []): array;

    /**
     * Send an authenticated GET request.
     *
     * @return array<string, mixed>  Decoded JSON response
     */
    public function get(string $uri, array $query = []): array;

    /**
     * Send an unauthenticated GET request (public endpoints).
     *
     * @return array<string, mixed>  Decoded JSON response
     */
    public function getPublic(string $uri): array;
}
