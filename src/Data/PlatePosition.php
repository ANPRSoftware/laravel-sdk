<?php

namespace Anpr\LaravelSdk\Data;

final class PlatePosition implements \JsonSerializable
{
    public function __construct(
        private readonly array $data,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function width(): int
    {
        return ($this->data['x2'] ?? 0) - ($this->data['x1'] ?? 0);
    }

    public function height(): int
    {
        return ($this->data['y2'] ?? 0) - ($this->data['y1'] ?? 0);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
