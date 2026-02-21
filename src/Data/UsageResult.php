<?php

namespace Anpr\LaravelSdk\Data;

final class UsageResult implements \JsonSerializable
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

    public function toArray(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
