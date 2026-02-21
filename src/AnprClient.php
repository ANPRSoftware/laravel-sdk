<?php

namespace Anpr\LaravelSdk;

use Anpr\LaravelSdk\Contracts\AnprClientInterface;
use Anpr\LaravelSdk\Contracts\TransportInterface;
use Anpr\LaravelSdk\Data\DetectionResult;
use Anpr\LaravelSdk\Data\UsageResult;
use Anpr\LaravelSdk\Data\HealthResult;

class AnprClient implements AnprClientInterface
{
    public function __construct(
        private readonly TransportInterface $transport,
        private readonly string $apiVersion = 'v1',
    ) {}

    public function detect(mixed $image, array $params = []): DetectionResult
    {
        $data = $this->transport->postMultipart("/{$this->apiVersion}/detect", $image, $params);

        return DetectionResult::fromArray($data);
    }

public function usage(int $days = 30): UsageResult
    {
        $data = $this->transport->get("/{$this->apiVersion}/usage", ['days' => $days]);

        return UsageResult::fromArray($data);
    }

    public function health(): HealthResult
    {
        $data = $this->transport->getPublic('/health');

        return HealthResult::fromArray($data);
    }

}
