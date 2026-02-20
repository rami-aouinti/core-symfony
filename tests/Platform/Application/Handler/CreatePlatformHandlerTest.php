<?php

namespace App\Tests\Platform\Application\Handler;

use App\Platform\Application\Command\CreatePlatformCommand;
use App\Platform\Application\Handler\CreatePlatformHandler;
use App\Platform\Domain\Model\Platform;
use App\Platform\Domain\Repository\PlatformRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreatePlatformHandlerTest extends TestCase
{
    public function testHandlerCreatesAndPersistsPlatform(): void
    {
        $repository = new InMemoryPlatformRepository();
        $handler = new CreatePlatformHandler($repository);

        $result = $handler(new CreatePlatformCommand(
            name: 'Nintendo Switch',
            description: 'Console hybride',
            color: '#ffaa00',
            active: true,
        ));

        self::assertCount(1, $repository->savedPlatforms);
        self::assertSame('Nintendo Switch', $result->name);
        self::assertSame('Console hybride', $result->description);
        self::assertSame('#FFAA00', $result->color);
        self::assertTrue($result->active);
    }
}

final class InMemoryPlatformRepository implements PlatformRepositoryInterface
{
    /** @var Platform[] */
    public array $savedPlatforms = [];

    public function save(Platform $platform): void
    {
        $this->savedPlatforms[] = $platform;
    }
}
