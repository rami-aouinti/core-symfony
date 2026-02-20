<?php

namespace App\Platform\Infrastructure\Persistence;

use App\Entity\Platform as PlatformEntity;
use App\Platform\Domain\Model\Platform;
use App\Platform\Domain\Repository\PlatformRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrinePlatformRepository implements PlatformRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Platform $platform): void
    {
        $entity = (new PlatformEntity())
            ->setName($platform->name())
            ->setDescription($platform->description())
            ->setColor($platform->color())
            ->setActive($platform->isActive());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
