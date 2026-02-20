<?php

namespace App\Platform\Domain\Model;

use App\Platform\Domain\Exception\InvalidPlatformException;

final class Platform
{
    private function __construct(
        private string $id,
        private string $name,
        private string $description,
        private string $color,
        private bool $active,
    ) {
    }

    public static function create(string $name, string $description, string $color, bool $active): self
    {
        $name = trim($name);
        if ('' === $name) {
            throw new InvalidPlatformException('Platform name cannot be empty.');
        }

        $description = trim($description);
        if ('' === $description) {
            throw new InvalidPlatformException('Platform description cannot be empty.');
        }

        if (1 !== preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            throw new InvalidPlatformException('Platform color must be a valid hexadecimal color (#RRGGBB).');
        }

        return new self(
            id: bin2hex(random_bytes(16)),
            name: $name,
            description: $description,
            color: strtoupper($color),
            active: $active,
        );
    }

    public static function reconstitute(string $id, string $name, string $description, string $color, bool $active): self
    {
        return new self(
            id: $id,
            name: $name,
            description: $description,
            color: strtoupper($color),
            active: $active,
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
