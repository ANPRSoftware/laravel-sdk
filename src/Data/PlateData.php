<?php

namespace Anpr\LaravelSdk\Data;

final class PlateData implements \JsonSerializable
{
    private array $data;

    public readonly PlateText $text;
    public readonly PlateAttributes $attributes;
    public readonly ?PlatePosition $position;

    public function __construct(array $data)
    {
        $this->text = PlateText::fromArray($data['text'] ?? []);
        $this->attributes = PlateAttributes::fromArray($data['attributes'] ?? []);
        $this->position = isset($data['position'])
            ? PlatePosition::fromArray($data['position'])
            : null;

        $this->data = $data;
    }

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
