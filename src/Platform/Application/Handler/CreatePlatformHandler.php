<?php

namespace App\Platform\Application\Handler;

use App\Platform\Application\Command\CreatePlatformCommand;
use App\Platform\Application\DTO\PlatformCreatedDTO;
use App\Platform\Domain\Model\Platform;
use App\Platform\Domain\Repository\PlatformRepositoryInterface;

final readonly class CreatePlatformHandler
{
    public function __construct(private PlatformRepositoryInterface $platformRepository)
    {
    }

    public function __invoke(CreatePlatformCommand $command): PlatformCreatedDTO
    {
        $platform = Platform::create(
            name: $command->name,
            description: $command->description,
            color: $command->color,
            active: $command->active,
        );

        $this->platformRepository->save($platform);

        return PlatformCreatedDTO::fromDomain($platform);
    }
}
