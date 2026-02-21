<?php

namespace Anpr\LaravelSdk\Facades;

use Illuminate\Support\Facades\Facade;
use Anpr\LaravelSdk\Data\DetectionResult;
use Anpr\LaravelSdk\Data\UsageResult;
use Anpr\LaravelSdk\Data\HealthResult;
use Anpr\LaravelSdk\Testing\AnprFake;

/**
 * @method static DetectionResult  detect(mixed $image, array $params = [])
 * @method static UsageResult      usage(int $days = 30)
 * @method static HealthResult     health()
 *
 * @see \Anpr\LaravelSdk\AnprClient
 */
class Anpr extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'anpr';
    }

    /**
     * Replace the bound client with a fake for testing.
     */
    public static function fake(array $responses = []): AnprFake
    {
        $fake = new AnprFake($responses);

        static::swap($fake);

        return $fake;
    }
}
