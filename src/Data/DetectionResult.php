<?php

namespace Anpr\LaravelSdk\Data;

final class DetectionResult implements \JsonSerializable
{
    private array $data;

    /** @var PlateData[] */
    public readonly array $plates;

    public function __construct(array $data)
    {
        $this->plates = array_map(
            fn (array $p) => PlateData::fromArray($p),
            $data['plates'] ?? []
        );

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

    public function hasPlates(): bool
    {
        return ($this->data['plates_found'] ?? 0) > 0;
    }

    public function firstPlate(): ?PlateData
    {
        return $this->plates[0] ?? null;
    }

    public function plateTexts(): array
    {
        return array_map(fn (PlateData $p) => $p->text->fullEn(), $this->plates);
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
