<?php

namespace App\Platform\Infrastructure\Persistence\Doctrine\Repository;

use App\Platform\Domain\Model\Platform;
use App\Platform\Domain\Repository\PlatformRepository;
use App\Platform\Infrastructure\Persistence\Doctrine\Mapper\PlatformDoctrineMapper;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrinePlatformRepository implements PlatformRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PlatformDoctrineMapper $platformDoctrineMapper,
    ) {
    }

    public function save(Platform $platform): void
    {
        $entity = $this->platformDoctrineMapper->toEntity($platform);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
