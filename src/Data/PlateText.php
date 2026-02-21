<?php

namespace Anpr\LaravelSdk\Data;

final class PlateText implements \JsonSerializable
{
    private array $data;

    /** @var PlateCharacter[] */
    public readonly array $characters;

    public function __construct(array $data)
    {
        $this->characters = array_map(
            fn (array $c) => PlateCharacter::fromArray($c),
            $data['characters'] ?? []
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

    /** Full plate text in English (e.g. "S 2393"). */
    public function fullEn(): string
    {
        return trim(($this->data['plate_code_en'] ?? '') . ' ' . ($this->data['plate_number_en'] ?? ''));
    }

    /** Full plate text in Arabic. */
    public function fullAr(): string
    {
        return trim(($this->data['plate_code_ar'] ?? '') . ' ' . ($this->data['plate_number_ar'] ?? ''));
    }

    public function __toString(): string
    {
        return $this->fullEn();
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
