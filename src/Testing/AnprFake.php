<?php

namespace Anpr\LaravelSdk\Testing;

use Anpr\LaravelSdk\Contracts\AnprClientInterface;
use Anpr\LaravelSdk\Data\DetectionResult;
use Anpr\LaravelSdk\Data\UsageResult;
use Anpr\LaravelSdk\Data\HealthResult;
use PHPUnit\Framework\Assert;

class AnprFake implements AnprClientInterface
{
    private array $responses = [];
    private array $recorded  = [];

    public function __construct(array $responses = [])
    {
        $this->responses = $responses;
    }

    // --- Faking responses ---

    public function fakeDetection(DetectionResult $result): self
    {
        $this->responses['detect'] = $result;

        return $this;
    }

public function fakeUsage(UsageResult $result): self
    {
        $this->responses['usage'] = $result;

        return $this;
    }

    public function fakeHealth(HealthResult $result): self
    {
        $this->responses['health'] = $result;

        return $this;
    }

    // --- Interface methods ---

    public function detect(mixed $image, array $params = []): DetectionResult
    {
        $this->recorded[] = ['method' => 'detect', 'image' => $image, 'params' => $params];

        if (isset($this->responses['detect'])) {
            return $this->responses['detect'];
        }

        return DetectionResult::fromArray(
            json_decode(file_get_contents(__DIR__ . '/Fixtures/detect-success.json'), true)
        );
    }

public function usage(int $days = 30): UsageResult
    {
        $this->recorded[] = ['method' => 'usage', 'days' => $days];

        if (isset($this->responses['usage'])) {
            return $this->responses['usage'];
        }

        return UsageResult::fromArray(
            json_decode(file_get_contents(__DIR__ . '/Fixtures/usage-free.json'), true)
        );
    }

    public function health(): HealthResult
    {
        $this->recorded[] = ['method' => 'health'];

        if (isset($this->responses['health'])) {
            return $this->responses['health'];
        }

        return HealthResult::fromArray([
            'status'    => 'healthy',
            'models'    => ['detection' => true, 'classification' => true, 'ocr' => true],
            'storage'   => true,
            'timestamp' => now()->toISOString(),
        ]);
    }

    // --- Assertions ---

    public function assertDetectCalled(?int $times = null): self
    {
        return $this->assertMethodCalled('detect', $times);
    }

public function assertUsageCalled(?int $times = null): self
    {
        return $this->assertMethodCalled('usage', $times);
    }

    public function assertNothingCalled(): self
    {
        Assert::assertEmpty($this->recorded, 'Expected no API calls to be made.');

        return $this;
    }

    public function assertDetectCalledWithParams(array $expectedParams): self
    {
        return $this->assertMethodCalledWithParams('detect', $expectedParams);
    }

    private function assertMethodCalled(string $method, ?int $times): self
    {
        $calls = array_filter($this->recorded, fn ($r) => $r['method'] === $method);

        if ($times !== null) {
            Assert::assertCount($times, $calls, "Expected {$method}() to be called {$times} times.");
        } else {
            Assert::assertNotEmpty($calls, "Expected {$method}() to be called at least once.");
        }

        return $this;
    }

    private function assertMethodCalledWithParams(string $method, array $expectedParams): self
    {
        $calls = array_filter($this->recorded, fn ($r) => $r['method'] === $method);
        $found = false;

        foreach ($calls as $call) {
            if (($call['params'] ?? []) === $expectedParams) {
                $found = true;
                break;
            }
        }

        Assert::assertTrue($found, "Expected {$method}() to be called with specific params.");

        return $this;
    }
}
