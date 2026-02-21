<?php

namespace Anpr\LaravelSdk\Contracts;

use Anpr\LaravelSdk\Data\DetectionResult;
use Anpr\LaravelSdk\Data\UsageResult;
use Anpr\LaravelSdk\Data\HealthResult;

interface AnprClientInterface
{
    /**
     * Full detection pipeline: detection → classification → OCR.
     *
     * @param  string|resource|\SplFileInfo  $image   File path, stream, or file object
     * @param  array<string, mixed>          $params  Custom metadata forwarded to webhooks
     */
    public function detect(mixed $image, array $params = []): DetectionResult;

/**
     * Get usage statistics and remaining credits.
     *
     * @param  int  $days  Number of days to include in usage history (default: 30)
     */
    public function usage(int $days = 30): UsageResult;

    /** Health check — returns model readiness status. */
    public function health(): HealthResult;
}
