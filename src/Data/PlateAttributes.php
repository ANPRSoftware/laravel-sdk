<?php

namespace Anpr\LaravelSdk\Data;

final class PlateAttributes implements \JsonSerializable
{
    public function __construct(
        private readonly array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }
}
