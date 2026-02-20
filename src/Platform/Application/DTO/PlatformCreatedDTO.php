<?php

namespace App\Platform\Application\DTO;

use App\Platform\Domain\Model\Platform;

final readonly class PlatformCreatedDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $color,
        public bool $active,
    ) {
    }

    public static function fromDomain(Platform $platform): self
    {
        return new self(
            id: $platform->id(),
            name: $platform->name(),
            description: $platform->description(),
            color: $platform->color(),
            active: $platform->isActive(),
        );
    }
}
