<?php

namespace App\Platform\Infrastructure\Persistence\Doctrine\Mapper;

use App\Entity\Platform as PlatformEntity;
use App\Platform\Domain\Model\Platform;

final class PlatformDoctrineMapper
{
    public function toEntity(Platform $platform, ?PlatformEntity $entity = null): PlatformEntity
    {
        $entity ??= new PlatformEntity();

        return $entity
            ->setName($platform->name())
            ->setDescription($platform->description())
            ->setColor($platform->color())
            ->setActive($platform->isActive());
    }

    public function toDomain(PlatformEntity $entity): Platform
    {
        return Platform::reconstitute(
            id: (string) $entity->getId(),
            name: (string) $entity->getName(),
            description: (string) $entity->getDescription(),
            color: (string) $entity->getColor(),
            active: (bool) $entity->isActive(),
        );
    }
}
