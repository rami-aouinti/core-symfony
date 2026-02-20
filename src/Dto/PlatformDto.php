<?php

namespace App\Dto;

use App\Entity\Platform;
use DateTimeImmutable;

final class PlatformDto
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $description,
        public ?string $slug,
        public bool $active,
        public ?string $color,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt,
    ) {
    }

    public static function fromEntity(Platform $platform): self
    {
        return new self(
            id: $platform->getId(),
            name: $platform->getName(),
            description: $platform->getDescription(),
            slug: $platform->getSlug(),
            active: $platform->isActive(),
            color: $platform->getColor(),
            createdAt: $platform->getCreatedAt(),
            updatedAt: $platform->getUpdatedAt(),
        );
    }
}
