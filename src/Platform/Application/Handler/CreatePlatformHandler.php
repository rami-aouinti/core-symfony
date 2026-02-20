<?php

namespace App\Platform\Application\Handler;

use App\Platform\Application\Command\CreatePlatformCommand;
use App\Platform\Application\DTO\PlatformCreatedDTO;
use App\Platform\Domain\Model\Platform;
use App\Platform\Domain\Repository\PlatformRepository;

final readonly class CreatePlatformHandler
{
    public function __construct(private PlatformRepository $platformRepository)
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
